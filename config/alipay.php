<?php
return [
    //应用ID,您的APPID。
    'app_id' => "2019012663097781",

    //商户私钥，您的原始格式RSA私钥
    'merchant_private_key' => "MIIEowIBAAKCAQEAzkHIz8mFgFZk2R+fDsXPEGSUzNM2Ns5x0e25cshWBgxChWm/WdaJPC1J6M++dxujqbzlO1ut4QM62mwV+8e6m+hkGRdiCsMMTUMudx1mID+N9E5N/SLsIj7pXXLmh98LZ6h01p+xnulbAYfaC4RpJprnhr7IWyMwe75Zwe/iDY/r/QzZsJNnK52epp04HD4LJRUnyFu/kY0+pNalr5VIOLCtbSAa9tMwMXVAXQiWWAlEHXji4HUN8ahdQmduwVeS9LnrFMVR38GSz1iU4dh4Wcshh3IRe3mG035OzD8a6ytHUBCcXpZAUuUKaWe+Y03izEUPt4WIgivv1iGWxltAhwIDAQABAoIBAGxEKcWwcYxRTTSPna1idfOT6Guvvrh8G1DnqT2wHT+lddGUFaZxr63P1AkJ61+YOYuipO1IjXgceusma0+zoqeuD8T/+i3N/oob5qsN2oq48am45MUobKz5OwQbiRjvIoxx54q1XsYclfGyvujmw09JZpjbUk5MpWl/HJxpb85PuWt+msGn90SFdcEyQ6WH4L2kFOsqiY8E1Zyj+QlR83iPxAgNFQLDs/ajSgwHq0Hg8jacjF9lcAl2YlppCUUlim02dSlZjq/tKReye/IGYhibnSpieYtEog7gQq9qVyn5hAlz+V+K/6gTsIJwylrkLA+Yn/8tj6hpHNSgJKFV3CkCgYEA7Bx6AyT/7vjiB8qEb/ChKDec3/asZGcey0wON95QgPd8QTsoW9eaeKz7Rx39rc0ZiUx1RxF+rRoMLzL5N8mv9OwGTRsvxerl8uUmivVqMbCPP0urCo1JoejxMh4+3fnxHHok0robx6UZD7cG0NRGWOfhqaALqZP9itMTLl54BbsCgYEA36GHHZiZ2uK45LOwQ+OkCxQwpoq7owpa46Um9IQ3zTn997wdFLHPJteJyDItmWOqVfLBm9p/lYehsCgYv/Oq3XQZrSwV01NgF94kpX6qAHMAWi2NPF0DoP4XMeuphhGpCGkrNOcKiF9tH6xg4JzUbB6uVtSST3aQ4giUA2HGPaUCgYEAwjYuOnXtuxCnQ4dWZolE1kLgW+yYIsIbt1do/pV0HJD1eOaDLQBshEStL/NAXfytu5zTftCqJRKe4RgJnNTeUhTune/13NN5r9DDYlu5rQOw4HyKRVSwM2jbNMFiLs5PgIAL1/XTseVgdueyyIVDFBAwM4l87lDXpaA3T9XO3c8CgYAQ365DRG1vf6X1070cEkQhdd+J4XPxBGoBPUfoMUXInXSVScNj+VqpEqBZ07dvGH8UxyFmsiDoVniTwyLC8Q7WcNkzIN1wuZzJkEBxoFIRgJvpMgCzKk2Iy0NSx/ZdoF2BvfW9oyU/Tvv75NTWfd7lXZdIA/gaONcLxxvr+Wes5QKBgGNkGxmIRrd1853i39hWiRqUTKZTTUBvicR+b/rzjVV9YxuHSqCJBJmpp4i3PNdfa8ApIHLMH9U1CsKurRA2Iv4SxK7CWIKSgMI9nPAlwxJgswnD9IRlFyJ/xyIE0PVblAbh6e7KoSVA4D3/0S802VPVCao6TN9gZCGzDDowf3eq",
    
    //异步通知地址
    'notify_url' => "https://www.cjlndx.top/alipay_notify",
    
    //同步跳转
    'return_url' => "https://www.cjlndx.top/alipay_return",

    //编码格式
    'charset' => "UTF-8",

    //签名方式
    'sign_type'=>"RSA2",

    //支付宝网关
    'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

    //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
    'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAgATvGI8OqJZXsZsTIiIvPDRLjhGTv0m6qtY2EH9wwLAzPEBUTPZZNt3RyCKcutflpJjBYQA2IXPCWA4C/eHed1kuLhfhoS2/lMXi/t1j4gdwTzTByDdnaG8L7ys3IMMyaRwL2wvO6gKwd2JlICUCJNyjAcPJARby2jPd7XjBAmKq00S6ROrHmnbY/dxpeDJNzvIkqfNJ1L9ACYghsCbHC3DgE+Of2ui5m7KBbNmBoBrlG4p/ofBWU6M9nRP4lFy9LJFM4iYhR6z5bQ38hVslCUIIb9J+0aFinf9yZUdjqBTat+JscYPDRZmVDC/6gNOaRRPVkANDXAK9CUEoiSQcLQIDAQAB",
];