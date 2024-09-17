# silverstripe-well-known-validation

> A [SilverStripe](https://www.silverstripe.org/) module for conveniently allowing administrators to validate the ownership of their website or domain name with other third-party services.

## FAQ

This section covers frequently asked questions.

### Q: What does this module do?

This module manages routing for the `/.well-known` endpoint, as specified by RFC 8615, to display descriptive information about a website and its services. The endpoint is commonly used by third-party services (such as [Keybase.io](https://keybase.io)) for domain and website ownership verification.

With this module, you can handle validation requests directly from your SilverStripe administration panel, eliminating the need to upload verification files manually to your web server.

### Q: How does it work?

To use the module, follow the installation instructions below and run `/dev/build?flush=all`. Once installed, go to the **Validation** tab in your SilverStripe project's Configuration panel to manage validation settings.

## Requirements

* **SilverStripe** `^4.0 | ^5.0`

## Installation

```bash
composer require loveduckie/silverstripe-well-known-validation
```

**Note:** When you have completed your module, submit it to Packagist or add it as a VCS repository to your
project's `composer.json`, pointing to the private repository URL.

## License

See [License](license.md)

## Documentation

* [Documentation readme](docs/en/readme.md)

```yaml

Page:
  config_option: true
  another_config:
    - item1
    - item2

```

### Configuration

This section outlines any additional configuration that may be required. This module should work automatically out of the box once you have run `/dev/build?flush=all`, but you may wish to configure it further so that it can support other third-party services.

#### NGINX

It is assumed that you are serving your SilverStripe project from a NGINX server with a configuration that looks similar to the following.

```nginx
map $http_accept $webp_suffix
{
  default   "";
  "~*webp"  ".webp";
}

server
{
  listen 80;
  listen [::]:80;
  server_name ${WEBSITE_DOMAIN_NAMES};
  server_tokens off;
  return 301 https://${WEBSITE_DOMAIN_NAME}$request_uri;
}

server
{
  listen 443 ssl http2;
  listen [::]:443 ssl http2;
  server_name ${WEBSITE_DOMAIN_NAMES};
  server_tokens off;
  charset utf-8;

  ssl_certificate ${NGINX_CONFIG_CERTS_PATH}/fullchain.pem;
  ssl_certificate_key ${NGINX_CONFIG_CERTS_PATH}/privkey.pem;

  access_log /var/log/nginx/sites/${WEBSITE_DOMAIN_NAME}.access combined;
  error_log /var/log/nginx/sites/${WEBSITE_DOMAIN_NAME}.error warn;

  include /etc/nginx/mime.types;

  client_max_body_size 0; # Manage this in php.ini (upload_max_filesize & post_max_size)

  root /var/www/sites/${PRODUCTION_WEBSITE_DOMAIN_NAME}/public;

  # Defend against SS-2015-013 -- http://www.silverstripe.org/software/download/security-releases/ss-2015-013
  if ($http_x_forwarded_host) {
    return 400;
  }

  location / {
      try_files $uri /index.php?$query_string;
  }

  error_page 404 /assets/error-404.html;
  error_page 500 /assets/error-500.html;
  error_page 502 /assets/error-500.html;
  error_page 503 /assets/error-500.html;

  location ~* /assets/.+\.(?<extension>jpe?g|png|gif|webp)$
  {
    gzip_static on;
    gzip_types image/png image/x-icon image/webp image/svg+xml image/jpeg image/gif;

    add_header Vary Accept;
    expires max;
    sendfile on;
    try_files "${request_uri}.webp" "${request_uri}" $uri =404;
  }

  location ~* ^/assets/.*
  {
    gzip_static on;
    gzip_types text/plain text/xml text/css
    text/comma-separated-values application/json
    image/png image/jpeg image/x-icon image/webp image/svg+xml image/gif
    text/javascript application/x-javascript application/pdf
    application/atom+xml;

    expires max;
    sendfile on;
    try_files $uri =404;
  }

  location ^~ /resources/
  {
    gzip_static on;
    gzip_types text/plain text/xml text/css
    text/comma-separated-values application/json
    image/png image/x-icon image/webp image/svg+xml image/jpeg image/gif
    text/javascript application/x-javascript application/javascript
    application/atom+xml;

    expires max;
    sendfile on;
    try_files $uri =404;
  }

  # location ^~ /assets/ {
  #   gzip_static on;
  #   gzip_types text/plain text/xml text/css
  #   text/comma-separated-values application/json
  #   image/png image/jpeg image/x-icon image/webp image/svg+xml image/gif
  #   text/javascript application/x-javascript
  #   application/atom+xml;

  #   expires max;
  #   sendfile on;
  #   try_files $uri =404;
  # }

  location /index.php {
      # client_header_timeout 10000;
      # client_body_timeout 10000;
      fastcgi_read_timeout 10000;

      fastcgi_buffers 4 65k;
      fastcgi_buffer_size 64k;
      fastcgi_busy_buffers_size 128k;
      fastcgi_keep_conn on;
      fastcgi_pass   portfolio-php:9000;
      fastcgi_index  index.php;
      fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
      include        /etc/nginx/fastcgi_params;
  }

  # # Used temporarily for installation scripts
  # location ~ \.php$ {
  #     fastcgi_buffers 4 65k;
  #     fastcgi_buffer_size 64k;
  #     fastcgi_busy_buffers_size 128k;
  #     fastcgi_keep_conn on;
  #     fastcgi_pass   portfolio-php:9000;
  #     fastcgi_index  index.php;
  #     fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
  #     include        fastcgi_params;
  # }
}
```

## Maintainers

* Luc Shelton <lucshelton@gmail.com>

## Bugtracker

Bugs are tracked in the issues section of this repository. Before submitting an issue please read over
existing issues to ensure yours is unique.

If the issue does look like a new bug:

1. Create a new issue
2. Describe the steps required to reproduce your issue, and the expected outcome. Unit tests, screenshots
 and screencasts can help here.
3. Describe your environment as detailed as possible: SilverStripe version, Browser, PHP version,
 Operating System, any installed SilverStripe modules.

Please report security issues to the module maintainers directly. Please don't file security issues in the bugtracker.

## Development and contribution

If you would like to make contributions to the module please ensure you raise a pull request and discuss with the module maintainers.
