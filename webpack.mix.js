import { js } from 'laravel-mix';

js('resources/js/app.js', 'public/js')
  .postCss('resources/css/app.css', 'public/css', [
    require('tailwindcss'),
  ])
  .sass('resources/sass/app.scss', 'public/css').options({
      processCssUrls: false
  })
  .version();