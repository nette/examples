# Nette Framework 3 + Webpack 4

This is just for education.

There is a simple example of Nette Framework with jQuery 3, bootstrap 4 and [Naja](https://github.com/jiripudil/Naja).
Naja is modern ES6-like library for Nette snippets ajaxification.

These assets are compiled and bundled together with Webpack.

## Requirements

- Nette 3.0+
- PHP 7.3+

## Development

- `git clone https://github.com/nette/examples.git`
- `cd Webpack/`
- `cp app/config/config.local.neon.dist app/config/config.local.neon`
- `composer install`
- `npm install`
- `npm run dev`
- `php -S 0.0.0.0:8000 -t www`

## Deployment

- `npm run build`

## Features

- :+1: Nette 3.0
- :+1: Webpack configuration
    - :tada: extracting JS to single bundle
    - :tada: extracting CSS to single file
    - :tada: more bundles (front/admin/vendor)
- :+1: Snippets
    - :tada: few snippets
- :+1: Nette Form
    - :tada: AJAX submitting
    - :tada: form builder
        - empty value on control (`@` in email)
        - validation rules (filled + email)
        - simple filter (transform email to lowercase)
        - onValidate / onSubmit / onSuccess
    - :tada: manual rendering
        - success snippet / error snippet
        - required class on form-group
        - description on control

## Roadmap

- :question: pure sendPayload method
- :question: dynamic snippets ( + snippetArea )
- :question: Vue.js component

## Result

### Webpack

![Webpack](https://raw.githubusercontent.com/nette/examples/master/Webpack/.docs/webpack.png)

### PHP Development Server

![PHP](https://raw.githubusercontent.com/nette/examples/master/Webpack/.docs/phpserver.png)

### Browser

![Web](https://raw.githubusercontent.com/nette/examples/master/Webpack/.docs/web.png)
