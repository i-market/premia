import phantomcss from 'phantomcss';
// suppress editor warnings
const casper = casper;

phantomcss.init({
  screenshotRoot: './e2e-tests/screenshots',
  failedComparisonsRoot: false,
  rebase: casper.cli.get('rebase')
});

const url = casper.cli.get('url');
const viewports = [
  {
    'name': 'smartphone-portrait',
    'viewport': {width: 320, height: 480}
  },
  {
    'name': 'smartphone-landscape',
    'viewport': {width: 480, height: 320}
  },
  {
    'name': 'tablet-portrait',
    'viewport': {width: 768, height: 1024}
  },
  {
    'name': 'tablet-landscape',
    'viewport': {width: 1024, height: 768}
  },
  {
    'name': 'desktop-standard',
    'viewport': {width: 1280, height: 1024}
  }
];

casper.start(url, function() {
  this.echo(this.getCurrentUrl());
});

casper.each(viewports, function(casper, viewport) {
  this.then(function() {
    this.viewport(viewport.viewport.width, viewport.viewport.height, function() {
      phantomcss.screenshot('body', `${viewport.name} full page`);
    });
  });
});

casper.then(function() {
  phantomcss.compareAll();
});

casper.run();
