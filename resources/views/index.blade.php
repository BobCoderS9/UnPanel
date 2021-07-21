<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="icon" href="/favicon.ico">
    <title>{{ sysConfig('website_name') }}</title>
    <link href="/css/chunk-002d52eb.5b3eb35f.css" rel="prefetch">
    <link href="/css/chunk-0de32d87.3904bc56.css" rel="prefetch">
    <link href="/css/chunk-0f870f7e.749d4133.css" rel="prefetch">
    <link href="/css/chunk-13b05401.fbfbad4b.css" rel="prefetch">
    <link href="/css/chunk-231937e8.65bcb6fd.css" rel="prefetch">
    <link href="/css/chunk-3fb39ce6.c44c21b4.css" rel="prefetch">
    <link href="/css/chunk-6b665686.77b536a1.css" rel="prefetch">
    <link href="/css/chunk-7660791e.97fe6a57.css" rel="prefetch">
    <link href="/css/chunk-7abda71a.0c316f23.css" rel="prefetch">
    <link href="/css/chunk-b305abc4.deba3cda.css" rel="prefetch">
    <link href="/css/chunk-da681e8c.34f5a2d8.css" rel="prefetch">
    <link href="/js/chunk-002d52eb.8ad41dd2.js" rel="prefetch">
    <link href="/js/chunk-018dd1b6.f5934b35.js" rel="prefetch">
    <link href="/js/chunk-0de32d87.a41a26b1.js" rel="prefetch">
    <link href="/js/chunk-0f870f7e.e29b60e7.js" rel="prefetch">
    <link href="/js/chunk-13b05401.1d2a5532.js" rel="prefetch">
    <link href="/js/chunk-231937e8.10cc4a28.js" rel="prefetch">
    <link href="/js/chunk-2d21af95.739f5cff.js" rel="prefetch">
    <link href="/js/chunk-3fb39ce6.bf69de3d.js" rel="prefetch">
    <link href="/js/chunk-6b665686.dcb639e1.js" rel="prefetch">
    <link href="/js/chunk-7660791e.da9735b3.js" rel="prefetch">
    <link href="/js/chunk-7abda71a.10e9c6ea.js" rel="prefetch">
    <link href="/js/chunk-b305abc4.21b85c39.js" rel="prefetch">
    <link href="/js/chunk-c5f0ec36.bcf662f6.js" rel="prefetch">
    <link href="/js/chunk-da681e8c.9869c3c2.js" rel="prefetch">
    <link href="/css/app.0f48ac8f.css" rel="preload" as="style">
    <link href="/css/chunk-vendors.57cd1a04.css" rel="preload" as="style">
    <link href="/js/app.13dbffaa.js" rel="preload" as="script">
    <link href="/js/chunk-vendors.dabdc617.js" rel="preload" as="script">
    <link href="/css/chunk-vendors.57cd1a04.css" rel="stylesheet">
    <link href="/css/app.0f48ac8f.css" rel="stylesheet">
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
<script src="/js/chunk-vendors.dabdc617.js"></script>
<script src="/js/app.13dbffaa.js"></script>
</body>
</html>
