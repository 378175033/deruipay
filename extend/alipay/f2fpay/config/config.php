<?php
$config = array (
		//签名方式,默认为RSA2(RSA2048)
		'sign_type' => "RSA2",

		//支付宝公钥
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwg8LiXU5Cj0EFgaFEpbOo7cpRGNVbOxRLo4ZjfC/bgn1Aehe9lvSntxjwUcsujzn2m/EPnp6iFAfgGeXUE8/rLFumpPwtUxfyPUBVsoAAZ1zubMxkrySUzq9yKSPfhmug/cIzHwcjsv19QsmEm+o2so63uuNcsOe/eGQuSSy+RaiR7Gy8jIBhwYB/dt3KQL5gKm3V9W8OOYpK9dw5feNGinSpbET2T+14UWcVq2Lr7WQ5YFNs+ZekO1v+866o+WiG21IHcbFQJDXTW+DNVSXfsI9RxapB+VSgBApEXUaCCa+W5SEEbQNLfnxCMRLkBb4xBVnHCianjINZO0x2LWIcwIDAQAB",
		//商户私钥
		'merchant_private_key' => "MIIEpAIBAAKCAQEAwg8LiXU5Cj0EFgaFEpbOo7cpRGNVbOxRLo4ZjfC/bgn1Aehe9lvSntxjwUcsujzn2m/EPnp6iFAfgGeXUE8/rLFumpPwtUxfyPUBVsoAAZ1zubMxkrySUzq9yKSPfhmug/cIzHwcjsv19QsmEm+o2so63uuNcsOe/eGQuSSy+RaiR7Gy8jIBhwYB/dt3KQL5gKm3V9W8OOYpK9dw5feNGinSpbET2T+14UWcVq2Lr7WQ5YFNs+ZekO1v+866o+WiG21IHcbFQJDXTW+DNVSXfsI9RxapB+VSgBApEXUaCCa+W5SEEbQNLfnxCMRLkBb4xBVnHCianjINZO0x2LWIcwIDAQABAoIBACrCt+8VFnmMEl9sFlyPQH9Qt9Yq8ULsG8NfaoAdYYE0znkaI/qzJwj8VTrcnR14mDpI0HxX7rIkvZxEt1Hp9ITwIAgNu0enyZ91ZVMjdbblY/+yXaUQyklusy0IHdpSfGL1x0mPu5c3mD3jtALx+cokL665RtTYCCu3TXWOgaVjFiog8V3E0cEl1ekPxGH9+BuWqeIhq61KmbluJWtsvKauCj8XbEXfJ7Q1SLlbUOjJYT9bWWsfUPE7TBFbUSuC9KK8NQYebXwwBExNAMIU5mbwYKIcWKAD2skNw0RVk2g5bUPQcMd4Vl++BS9DMvTCt9wL8gNWT3RSVgnaJpsQBXECgYEA4Jou0pkHm/RvuOT8Si5PtMSKf4i9HlLzd3TEpI6/mHBDwydDptbYNAwHfH2zQMkynw2JDqg0NL9VMHATJUmrPm6wB+lzajXUw4VO0g48xC8lTzi1nYGnr6Zg//0T3Gjt4cvAoeO/Q05w5Su8quEgVEBsSaKn8zdjIqqCL4iXQ2sCgYEA3S/OFaRF3WRci/LaOM7ECWjOw1yoFlaVGABKHCyig2D5WWEBh+6T3k9UsUVhJEVUaPC5Y6Tq8Vj5UVCR5a2LT9fodN3O8I+S/peMr4m73YZXUoafriX0jMyXfSQJLRF1tfKOZlIHedU/G2e9OOiVPOJ4PWoJ4nm6HcwWua4vmRkCgYAbwLF8cFBSYvfTHuhVujc7HPYIIDtOHe3bmuAZfVILYgPdf2KKoQ2CEOJz7YxSuwm4QZHn77zTr7i1DYQwHVQ9mKvDroMGYrRxnG1K41t62mB/04ANgFHaEHL37quflo+eUPDykBO4G18z0h2z97Fo97TpvGGIWhWz2OHRQc1/FQKBgQCejOALP2AdXQ3B++lVg1Ge9RQRkl+i85mYRMza+VvdFSxoV1MDn487cl5hXDxQBaqGNtiNhvAq5P6CvWB35TjRmRE2hLEMW76g5P2h7vdNyjjaHUplSSvNqfKFb8lsFvHr5N0Sl4ZoXOYJvQk0u/QOWsCaNWK0h1FUfrFjlGrmMQKBgQC0nIaylG7kqwOvcj2oNR5EjfELQgx7BGGhYUoKXy4WgWi+kDWV4vuIeImt9xJ3xGx5aX77tnp8iUIcVEHYzAieH3O41uBkBpXCPb71+XHBhBFnXULfPslO8o4OROWBXEQPlevaQjTb3nxGmXhTOcBEt5zdH2xa+p8jqGegHXXuAg==",

		//编码格式
		'charset' => "UTF-8",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//应用ID
		'app_id' => "2017061607505957",

		//异步通知地址,只有扫码支付预下单可用
		'notify_url' => "http://play.larevl.com/index/notify/aliPayNotify",

		//最大查询重试次数
		'MaxQueryRetry' => "10",

		//查询间隔
		'QueryDuration' => "3"
);