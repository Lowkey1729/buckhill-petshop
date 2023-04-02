import SwaggerUI from 'swagger-ui'
import 'swagger-ui/dist/swagger-ui.css';

let host = location.protocol + '//' + location.host
SwaggerUI({
    dom_id: '#swagger-api',
    url: host + '/petshop-swagger.yml',
});
