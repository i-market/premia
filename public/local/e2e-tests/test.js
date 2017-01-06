import phantomcss from 'phantomcss';
// suppress editor warnings
const casper = casper;

phantomcss.init({
  screenshotRoot: './e2e-tests/screenshots',
  failedComparisonsRoot: false,
  rebase: casper.cli.get('rebase')
});

// TODO config url
casper.start('http://bitrix.localhost/', function() {
  this.echo(this.getTitle());
  phantomcss.screenshot('body', 'full page');
});

casper.then(function() {
  phantomcss.compareAll();
});

casper.run();
