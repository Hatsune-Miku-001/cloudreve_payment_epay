# PHP-Based Cloudreve Pro Payment Relay

这是一个用于Cloudreve Pro的支付中转件，基于PHP开发，旨在简化和安全地处理支付流程。

## 如何使用

您只需将文件部署到支持PHP的HTTP服务器上（例如NGINX、Apache等），即可开始使用这个中转件。

如果您希望节省部署时间并立即使用，我们还提供免费的在线服务：

1. 在Cloudreve Pro的仪表盘中，依次进入“参数设置” -> “增值服务” -> “自定义付款渠道”。
2. 在“支付接口地址”栏中输入以下格式的内容：

   ```
   https://api.lucloud.top/api/v2/payment/pay_gateway.php?payment=<您的易支付API接口地址>&pid=<您的商户号>&key=<您的商户密钥>&type=<所需支付方式>
   ```

   示例：如果您的易支付网站API接口地址为`https://example.com/api.php`，商户号为`123456`，密钥为`abcdefg`，支付方式为支付宝（`alipay`），那么完整的接口地址为：

   ```
   https://api.lucloud.top/api/v2/payment/pay_gateway.php?payment=https://example.com/api.php&pid=123456&key=abcdefg&type=alipay
   ```

## 支持的支付方式

- 微信支付: `wxpay`
- QQ支付: `tenpay`
- 支付宝: `alipay`

请注意，支持的具体支付方式取决于您所选择使用的易支付平台设置。

## 联系我们

欢迎加入我们的QQ群：[565715364](https://qm.qq.com/q/jmyvgV4rOE) 来获取支持和交流。(也可以来聊天吹水)

<img src="https://static-smikuy-oss.lucloud.top/img/upload/PicGo202411191830583.webp?x-oss-process=style/webp" alt="QQ群二维码" style="zoom:33%;" />

希望这个中转件能够为您带来方便，如有任何问题，请随时与我们联系。谢谢！
