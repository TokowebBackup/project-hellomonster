> Underdevelopment By Puji Ermanto<pujiermanto@gmail.com> | AKA Vickerness

### For smtp setup dmrc
✅ Tambahkan ini ke DNS zone editor (di cPanel/cloud hosting DNS):
SPF record:
```
v=spf1 +a +mx +ip4:IP_KAMU include:_spf.hostinger.com ~all
```
> (Ganti IP_KAMU dengan IP server hosting kamu)

- DKIM: minta ke penyedia hosting kamu. Biasanya ada opsi Enable DKIM di cPanel > Email > Email Deliverability

- DMARC (optional tapi penting):
```
_dmarc TXT "v=DMARC1; p=none; rua=mailto:dmarc@hellomonster.demo-tokoweb.my.id"
```