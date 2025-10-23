const puppeteer = require('puppeteer');

(async () => {
  const browser = await puppeteer.launch();
  const page = await browser.newPage();
  await page.goto('https://naver.com');
  await page.screenshot({ path: 'naver.png' });
  await browser.close();
})();
