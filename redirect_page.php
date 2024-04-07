<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $is_chinese ? '安全重定向' : 'Secure Redirect'; ?></title>
    <script>
        function countdown() {
            var seconds = <?php echo $setting['seconds']; ?>;
            var countdown = document.getElementById('countdown');
            var interval = setInterval(function() {
                countdown.innerHTML = seconds;
                seconds--;
                if (seconds < 0) {
                    clearInterval(interval);
                    window.location.href = '<?php echo $url; ?>'; // Redirect
                }
            }, 1000);
        }
    </script>
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        .advertise, .advertise iframe {
            width: 300px;
            height: 300px;
            margin: 0 auto;
        }
        button {
            margin: 20px auto;
            width: 300px;
            height: 36px;
            background: #4AB2FF;
            border: 0;
            color: #fff;
            font-size: 16px;
            display: block;
        }
        p {
            padding: 0 20px;
        }
        .center {
            text-align: center;
        }
        p span {
            color: red;
        }
    </style>
</head>
<body onload="countdown()">
<h2 class="center"><?php echo $is_chinese ? '注意' : 'Attention'; ?></h2>
<p class="center"><?php echo $is_chinese ? '注意下一页的安全性。' : 'Be cautious of the security on the upcoming page.'; ?></p>
<?php if ($setting['html']): ?>
    <div class="advertise"><?php echo $setting['html']; ?></div>
<?php endif; ?>
<button onclick="window.location.href = '<?php echo $url; ?>';"><?php echo $is_chinese ? '立即跳转' : 'Redirect Now'; ?></button>
<?php if ($is_chinese): ?>
    <p class="center"><span id="countdown"><?php echo $setting['seconds']; ?></span> 秒后自动跳转...</p>
<?php else: ?>
    <p class="center">Redirecting in <span id="countdown"><?php echo $setting['seconds']; ?></span> seconds...</p>
<?php endif; ?>
<p><?php echo $is_chinese ? '下一页的网址为：' : 'The URL for the next page is:'; ?><span><?php echo $url; ?></span></p>
<p><?php echo $is_chinese ? '在输入用户信息前务必确认网站安全性，谨防假冒网站。' : 'Be sure to confirm the security of the website before entering user information and beware of fake websites.'; ?></p>
</body>
</html>
