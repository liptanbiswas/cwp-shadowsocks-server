# cwp-shadowsocks-server
CentOS Webpanel Module to install,manage and remove Shadowsocks-libev server. I use Shadowsocks to unblock censorship. I also use CWP to host my personal site. Why not host both together :smiley:.

### How to Install

Just move `ss_server.php` to `/usr/local/cwpsrv/htdocs/resources/admin/modules` directory. You can run following command to do this directly.

```bash
curl -o /usr/local/cwpsrv/htdocs/resources/admin/modules/ss_server.php\
    https://raw.githubusercontent.com/liptanbiswas/cwp-shadowsocks-server/master/ss_server.php
```

The module will now be available on `/index.php?module=ss_server`

You can also add a link to the CWP menu by running.

```bash
echo '<li><a href="index.php?module=ss_server"><span class="icon16 icomoon-icon-arrow-right-3"></span>Shadowsocks</a></li>'\
    >> /usr/local/cwpsrv/htdocs/resources/admin/include/3rdparty.php
```
Module will now be available under **Developer Menu >> Shadowsocks**.

### Screenshots

**Not yet installed**

![not installed](https://raw.githubusercontent.com/liptanbiswas/cwp-shadowsocks-server/master/ss_notinstalled.png)

**Installation**

![installation](https://raw.githubusercontent.com/liptanbiswas/cwp-shadowsocks-server/master/ss_installation.png)

**Configuration**

![configuration](https://raw.githubusercontent.com/liptanbiswas/cwp-shadowsocks-server/master/ss_configure.png)
