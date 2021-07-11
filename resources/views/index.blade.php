<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="icon" href="/favicon.ico">
    <title>{{ sysConfig('website_name') }}</title>
    <link href="/css/chunk-04625ef0.749d4133.css" rel="prefetch">
    <link href="/css/chunk-13b1b032.fbfbad4b.css" rel="prefetch">
    <link href="/css/chunk-281b4e61.97fe6a57.css" rel="prefetch">
    <link href="/css/chunk-33c5f8dd.c44c21b4.css" rel="prefetch">
    <link href="/css/chunk-3dddd002.3904bc56.css" rel="prefetch">
    <link href="/css/chunk-5ddabf32.5b3eb35f.css" rel="prefetch">
    <link href="/css/chunk-66ec95bf.1aa27738.css" rel="prefetch">
    <link href="/css/chunk-7587ef6a.1c7c9ee7.css" rel="prefetch">
    <link href="/css/chunk-dc75243c.0c316f23.css" rel="prefetch">
    <link href="/css/chunk-e56bb93c.a1872637.css" rel="prefetch">
    <link href="/css/chunk-f7105372.65bcb6fd.css" rel="prefetch">
    <link href="/js/chunk-018dd1b6.f5934b35.js" rel="prefetch">
    <link href="/js/chunk-04625ef0.dc9afc63.js" rel="prefetch">
    <link href="/js/chunk-13b1b032.8886c14c.js" rel="prefetch">
    <link href="/js/chunk-281b4e61.8f850140.js" rel="prefetch">
    <link href="/js/chunk-2d21af95.3a4629cd.js" rel="prefetch">
    <link href="/js/chunk-33c5f8dd.208e2ec3.js" rel="prefetch">
    <link href="/js/chunk-3dddd002.6e36a4d0.js" rel="prefetch">
    <link href="/js/chunk-5a6240f8.2ef3c47b.js" rel="prefetch">
    <link href="/js/chunk-5ddabf32.805a64b7.js" rel="prefetch">
    <link href="/js/chunk-66ec95bf.032550a9.js" rel="prefetch">
    <link href="/js/chunk-7587ef6a.fc847df5.js" rel="prefetch">
    <link href="/js/chunk-dc75243c.cdbd023d.js" rel="prefetch">
    <link href="/js/chunk-e56bb93c.621b6e38.js" rel="prefetch">
    <link href="/js/chunk-f7105372.4826d143.js" rel="prefetch">
    <link href="/css/app.d151f322.css" rel="preload" as="style">
    <link href="/css/chunk-vendors.d7fca6ff.css" rel="preload" as="style">
    <link href="/js/app.1fbbb016.js" rel="preload" as="script">
    <link href="/js/chunk-vendors.3d64b166.js" rel="preload" as="script">
    <link href="/css/chunk-vendors.d7fca6ff.css" rel="stylesheet">
    <link href="/css/app.d151f322.css" rel="stylesheet">
    <script>
        const VUE_SITE = "{{ sysConfig('website_url') }}/#"; // 填写前端域名
        const SITE_URL = "{{ sysConfig('website_url') }}"; // 填写后端域名
        const SITE_NAME = "{{ sysConfig('website_name') }}";  // 站点名称
    </script>
</head>
<body>
<div id="app"></div>
<script>
    const SCRIPT_ID = ""; // 填写Crisp_id即可开启crisp客服
    if (SCRIPT_ID) {
        window.$crisp = [];
        window.CRISP_WEBSITE_ID = SCRIPT_ID;
        (function () {
            d = document;
            s = d.createElement("script");
            s.src = "https://client.crisp.chat/l.js";
            s.async = 1;
            d.getElementsByTagName("head")[0].appendChild(s);
        })();
    }
</script>
<script src="/js/chunk-vendors.3d64b166.js"></script>
<script src="/js/app.1fbbb016.js"></script>
</body>
</html>
