<?php

namespace App\Services\JWT;

use App\Models\User;
use DateTimeImmutable;
use Exception;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\IdentifiedBy;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;
use Lcobucci\JWT\Validation\Validator;
use Psr\Clock\ClockInterface;
use Psr\Clock\ClockInterface as Clock;

final class WebTokenService
{
    protected Builder $tokenBuilder;

    protected Sha256 $signer;

    protected InMemory $signingKey;

    protected Parser $parser;

    protected ?User $user;

    protected DateTimeImmutable $expiresAt;

    protected DateTimeImmutable $now;

    protected ClockInterface $clock;

    protected string $appUrl;

    /**
     * @throws Exception
     */
    public function __construct(?User $user)
    {
        $this->parser = new Parser(new JoseEncoder());
        $this->tokenBuilder = (new Builder(
            new JoseEncoder(),
            ChainedFormatter::default()
        ));
        $this->signer = new Sha256();
        $this->signingKey = InMemory::plainText(random_bytes(32));
        $this->user = $user;
        $this->now = new DateTimeImmutable();
        $this->expiresAt = $this->now->modify(config('jwt.expiration'));
        $this->appUrl = config('app.url');
        $this->clock = new class () implements Clock {
            public function now(): DateTimeImmutable
            {
                return new DateTimeImmutable();
            }
        };
    }

    /**
     * Token is issued based on the request host,
     * the user uuid and the time it should it expire
     */
    public function issueToken(): string
    {
        $token = $this->tokenBuilder
            ->issuedBy(config('app.url'))
            ->permittedFor(config('app.url'))
            ->identifiedBy($this->user?->uuid)
            ->issuedAt($this->now)
            ->canOnlyBeUsedAfter($this->now->modify('+0 minute'))
            ->expiresAt($this->expiresAt)
            ->withHeader('alg', $this->signer->algorithmId())
            ->getToken($this->signer, $this->signingKey);

        return $token->toString();
    }

    /**
     * This validates the token based on if the request host that issued it,
     * the user uuid and the time it expires matches that of the token
     *
     * @param  non-empty-string  $token
     */
    public function validateToken(string $token): bool
    {
        $parsedToken = $this->parser->parse($token);

        return (new Validator())->validate(
            $parsedToken,
            new IdentifiedBy($this->user?->uuid),
            new IssuedBy(config('app.url')),
            (new StrictValidAt($this->clock))
        );
    }

    /**
     * @param  non-empty-string  $token
     */
    public function parseToken(string $token): string
    {
        $parsedToken = $this->parser->parse($token);
        assert($parsedToken instanceof UnencryptedToken);

        (new Validator())->assert(
            $parsedToken,
            new IdentifiedBy($this->user?->uuid),
            new IssuedBy(config('app.url'))
        );

        return $parsedToken->toString();
    }

    /**
     * @return DateTimeImmutable|false
     */
    public function getExpiresAt(): DateTimeImmutable|bool
    {
        return $this->expiresAt;
    }
}
