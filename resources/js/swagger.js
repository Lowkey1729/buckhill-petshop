import SwaggerUI from 'swagger-ui'
import 'swagger-ui/dist/swagger-ui.css';

let host = location.protocol + '//' + location.hostname
SwaggerUI({
    dom_id: '#swagger-api',
    url: host + '/api-docs.yml',
});
