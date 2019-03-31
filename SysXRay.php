<?php
/**
 * SysXRAY 1.0.3
 * 
 * Questa classe è da intendersi come un coltellino svizzero per ottenere info
 * sul sistema su cui gira php e qualche trick per visualizzare le 
 * configurazioni del client che visualizza direttamente la pagina. 
 * 
 * Only One Big Page!!!
 * 
 * Puoi usare i metodi di questa classe includendo questo file dove vuoi o 
 * puoi raggiungerelo direttamente (in quest'ultimo modo attualmente non è 
 * il massimo perchè non ci sono controlli lato sicurezza. EG: password o captha) 
 * Sappiate che se raggiungerete questa pagina direttamente verranno fatti 
 * partire a cascata tutti i check tra cui un portscanner rudimentale, quindi, 
 * consiglio di commentare o cancellare i controlli che non vi interessano.
 * 
 * Usate tutto quello messo a disposizione in questo file a vostro rischio e 
 * pericolo, se verrete bannati dal vostro hosting o qualsiasi altra cosa vi 
 * possa capitare di male la responsabilità non sarà nostra.
 * 
 * Attualmente SysXRay è assolutamente work in progress quindi è possibile che 
 * molte funzionalità non siano utilizzabili nell'infrastruttura nella quale 
 * lo farete girare, dipende anche dalla vostra fortuna e/o dalla capacità 
 * modificare questo file secondo esigenze.
 *  ____           __  ______             
 * / ___| _   _ ___\ \/ /  _ \ __ _ _   _ 
 * \___ \| | | / __|\  /| |_) / _` | | | |
 *  ___) | |_| \__ \/  \|  _ < (_| | |_| |
 * |____/ \__, |___/_/\_\_| \_\__,_|\__, |
 *        |___/                     |___/ 
 * 
 * @author n4d46t3m
 * @version 1.0.3
 * 
 */
if(basename(__FILE__)==basename($_SERVER['SCRIPT_FILENAME'])):
    $time = explode(' ', microtime());
    $start = $time = $time[1] + $time[0];
endif;

class SysXRay {

    private $swName = 'SysXRay';
    private $swVer = '1.0.3';
    private $portsWellKnown = [];
    private $portsRegistered = [];
    private $portsNotRegistered = [];

    function __construct(){
        error_log('Init SysXRay constructor');//DEBUG
        $this->setWellKnownPorts();
        $this->setRegisteredPorts();
        $this->setNotRegisteredPorts();
    }
    // Useful to get OS from user agent if you use this array keys with preg_match (can be faked easily)
    private $osList = array(
                            '/windows nt 10/i'      =>  'Windows 10',
                            '/windows nt 6.3/i'     =>  'Windows 8.1',
                            '/windows nt 6.2/i'     =>  'Windows 8',
                            '/windows ce/i'         =>  'Windows Mobile',
                            '/windows nt 6.1/i'     =>  'Windows 7',
                            '/windows nt 6.0/i'     =>  'Windows Vista',
                            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                            '/windows nt 5.1|windows xp/i' =>  'Windows XP',
                            '/windows nt 5.0/i'     =>  'Windows 2000',
                            '/windows nt 4.0|winnt|windows nt/i' =>  'Windows NT 4.0',
                            '/windows me/i'         =>  'Windows ME',
                            '/win98|windows 98/i'   =>  'Windows 98',
                            '/win95|windows_95|windows 95/i' =>  'Windows 95',
                            '/win16/i'              =>  'Windows 3.11',
                            '/macintosh|mac os x/i' =>  'Mac OS X',
                            '/mac_powerpc/i'        =>  'Mac OS 9',
                            '/linux/i'              =>  'Linux',
                            '/ubuntu/i'             =>  'Ubuntu',
                            '/open bsd/i'           =>  'OpenBSD',
                            '/sun os/i'             =>  'SunOS',
                            '/beos/i'               =>  'BeOS',
                            '/iphone/i'             =>  'iPhone',
                            '/ipod/i'               =>  'iPod',
                            '/ipad/i'               =>  'iPad',
                            '/android/i'            =>  'Android',
                            '/blackberry/i'         =>  'BlackBerry',
                            '/webos/i'              =>  'Mobile',
                            '/playstation 3/i'      =>  'PlayStation 3 OS',
                            '/playstation portable/i' =>  'Sony PSP',
                            '/wii/i' =>  'Nintendo WII'
                        );
    // Useful to get user client browser name from user agent if you use this array keys with preg_match (can be faked easily)
    private $browserList = array(
                            '/msie/i'       => 'Internet Explorer',
                            '/mspie/i'      => 'Pocket Internet Explorer',
                            '/firefox/i'    => 'Firefox',
                            '/safari/i'     => 'Safari',
                            '/chrome/i'     => 'Chrome',
                            '/edge/i'       => 'Edge',
                            '/opera/i'      => 'Opera',
                            '/qupzilla/i'   => 'QupZilla',
                            '/netscape/i'   => 'Netscape',
                            '/maxthon/i'    => 'Maxthon',
                            '/konqueror/i'  => 'Konqueror',
                            '/arora/i'      => 'Arora',
                            '/epiphany/i'   => 'Epiphany',
                            '/midori/i'     => 'Midori',
                            '/lynx/i'       => 'Lynx',
                            '/mobile/i'     => 'Handheld Browser'
                        );
    /**
     * setWellKnownPorts
     * Set a private class array containing Well Known Ports
     *
     * @return void
     * @author n4d46t3m
     * @version 1.0.1 
     */
    private function setWellKnownPorts(){
        // Incomplete, taken from https://it.wikipedia.org/wiki/Lista_di_porte_standard and
        // https://en.wikipedia.org/wiki/List_of_TCP_and_UDP_port_numbers#Well-known_ports
        $this->portsWellKnown[0]['tcp'] = 'In programming APIs (not in communication between hosts), requests a system-allocated (dynamic) port';
        $this->portsWellKnown[1]['tcp'] = 'TCP Multiplexor';
        $this->portsWellKnown[2]['tcp'] = 'compressnet Management Utility';
        $this->portsWellKnown[3]['tcp'] = 'compressnet Compression Process';
        $this->portsWellKnown[7]['udp'] = 'Echo Protocol';
        $this->portsWellKnown[8]['udp'] = 'Bif Protocol';
        $this->portsWellKnown[9]['udp'] = 'Discard Protocol';
        $this->portsWellKnown[13]['tcp'] = 'Daytime Protocol';
        $this->portsWellKnown[15]['tcp'] = 'Previously netstat service';
        $this->portsWellKnown[17]['tcp'] = 'Quote of the Day';
        $this->portsWellKnown[19]['udp'] = 'Chargen Protocol';
        $this->portsWellKnown[20]['tcp'] = 'FTP - Il file transfer protocol - data';
        $this->portsWellKnown[21]['tcp'] = 'FTP - Il file transfer protocol - control';
        $this->portsWellKnown[22]['tcp'] = 'SSH - Secure login, file transfer (scp, sftp) e port forwarding';
        $this->portsWellKnown[23]['tcp'] = 'Telnet insecure text communications';
        $this->portsWellKnown[25]['tcp'] = 'SMTP - Simple Mail Transfer Protocol (E-mail)';
        $this->portsWellKnown[42]['udp'] = 'Host Name Server Protocol';
        $this->portsWellKnown[43]['tcp'] = 'WHOIS';
        $this->portsWellKnown[53]['udp'] = 'DNS - Domain Name System';
        $this->portsWellKnown[67]['udp'] = 'BOOTP Bootstrap Protocol (Server) e DHCP Dynamic Host Configuration Protocol (Server)';
        $this->portsWellKnown[68]['udp'] = 'BOOTP Bootstrap Protocol (Client) e DHCP Dynamic Host Configuration Protocol (Client)';
        $this->portsWellKnown[69]['udp'] = 'TFTP Trivial File Transfer Protocol';
        $this->portsWellKnown[70]['tcp'] = 'Gopher';
        $this->portsWellKnown[79]['tcp'] = 'Finger';
        $this->portsWellKnown[80]['tcp'] = 'HTTP HyperText Transfer Protocol (WWW)';
        $this->portsWellKnown[80]['udp'] = 'QUIC, a transport protocol over UDP and has been renamed to HTTP/3, which is currently an Internet Draft';
        $this->portsWellKnown[81]['tcp'] = 'TorPark onion routing';
        $this->portsWellKnown[82]['udp'] = 'TorPark control';
        $this->portsWellKnown[88]['tcp'] = 'Kerberos Authenticating agent';
        $this->portsWellKnown[101]['tcp'] = 'NIC host name';
        $this->portsWellKnown[102]['tcp'] = 'ISO Transport Service Access Point (TSAP) Class 0 protocol';
        $this->portsWellKnown[104]['tcp'] = 'DICOM - Digital Imaging and Communications in Medicine';
        $this->portsWellKnown[109]['tcp'] = 'Post Office Protocol, version 3 (POP3) (E-mail)';
        $this->portsWellKnown[110]['tcp'] = 'POP3 Post Office Protocol (E-mail)';
        $this->portsWellKnown[113]['tcp'] = 'ident vecchio sistema di identificazione dei server';
        $this->portsWellKnown[115]['tcp'] = 'Simple File Transfer Protocol';
        $this->portsWellKnown[119]['tcp'] = 'NNTP usato dai newsgroups usenet';
        $this->portsWellKnown[123]['udp'] = 'NTP usato per la sincronizzazione degli orologi client-server';
        $this->portsWellKnown[137]['udp'] = 'NetBIOS Name Service';
        $this->portsWellKnown[138]['udp'] = 'NetBIOS Datagram Service';
        $this->portsWellKnown[139]['tcp'] = 'NetBIOS Session Service';
        $this->portsWellKnown[143]['tcp'] = 'IMAP4 Internet Message Access Protocol (E-mail)';
        $this->portsWellKnown[161]['udp'] = 'SNMP Simple Network Management Protocol (Agent)';
        $this->portsWellKnown[162]['udp'] = 'SNMP Simple Network Management Protocol (Manager)';
        $this->portsWellKnown[209]['tcp'] = 'Quick Mail Transfer Protocol';
        $this->portsWellKnown[300]['tcp'] = 'ThinLinc Web Access ';
        $this->portsWellKnown[308]['tcp'] = 'Novastor Online Backup';
        $this->portsWellKnown[311]['tcp'] = 'Mac OS X Server Admin officially AppleShare IP Web administration';
        $this->portsWellKnown[319]['udp'] = 'Precision Time Protocol (PTP) event messages';
        $this->portsWellKnown[320]['udp'] = 'Precision Time Protocol (PTP) general messages ';
        $this->portsWellKnown[388]['tcp'] = 'Unidata LDM near real-time data distribution protocol';
        $this->portsWellKnown[389]['tcp'] = 'LDAP';
        $this->portsWellKnown[411]['tcp'] = 'Direct Connect Usato per gli hub della suddetta rete';
        $this->portsWellKnown[443]['tcp'] = 'HTTPS usato per il trasferimento sicuro di pagine web';
        $this->portsWellKnown[445]['tcp'] = 'Microsoft-DS (Active Directory, share di Windows, Sasser-worm)';
        $this->portsWellKnown[445]['udp'] = 'Microsoft-DS SMB file sharing';
        $this->portsWellKnown[465]['tcp'] = 'SMTP - Simple Mail Transfer Protocol (E-mail) su SSL';
        $this->portsWellKnown[491]['tcp'] = 'GO-Global remote access and application publishing software';
        $this->portsWellKnown[500]['udp'] = 'Internet Security Association and Key Management Protocol (ISAKMP) / Internet Key Exchange (IKE)';
        $this->portsWellKnown[502]['tcp'] = 'Modbus';
        $this->portsWellKnown[514]['udp'] = 'SysLog usato per il system logging';
        $this->portsWellKnown[515]['tcp'] = 'Line Printer Daemon (LPD), print service';
        $this->portsWellKnown[517]['udp'] = 'Talk';
        $this->portsWellKnown[518]['udp'] = 'NTalk';
        $this->portsWellKnown[521]['udp'] = 'Routing Information Protocol Next Generation (RIPng) ';
        $this->portsWellKnown[525]['udp'] = 'Timed, Timeserver';
        $this->portsWellKnown[532]['tcp'] = 'netnews';
        $this->portsWellKnown[533]['udp'] = 'netwall, For Emergency Broadcasts ';
        $this->portsWellKnown[540]['tcp'] = 'Unix-to-Unix Copy Protocol (UUCP) ';
        $this->portsWellKnown[543]['tcp'] = 'klogin, Kerberos login ';
        $this->portsWellKnown[544]['tcp'] = 'kshell, Kerberos Remote shell ';
        $this->portsWellKnown[548]['tcp'] = 'Apple Filing Protocol (AFP) over TCP';
        $this->portsWellKnown[554]['udp'] = 'RTSP per lo streaming live';
        $this->portsWellKnown[556]['tcp'] = 'Remotefs, RFS, rfs_server ';
        $this->portsWellKnown[560]['udp'] = 'rmonitor, Remote Monitor ';
        $this->portsWellKnown[561]['udp'] = 'monitor';
        $this->portsWellKnown[563]['tcp'] = 'NNTP Network News Transfer Protocol (newsgroup Usenet) su SSL';
        $this->portsWellKnown[564]['tcp'] = '9P (Plan 9)';
        $this->portsWellKnown[587]['tcp'] = 'e-mail message submission (SMTP)';
        $this->portsWellKnown[591]['tcp'] = 'FileMaker 6.0 Web Sharing (HTTP Alternate, si veda la porta 80)';
        $this->portsWellKnown[601]['tcp'] = 'Reliable Syslog Service used for system logging ';
        $this->portsWellKnown[604]['tcp'] = 'TUNNEL profile, a protocol for BEEP peers to form an application layer tunnel';
        $this->portsWellKnown[623]['udp'] = 'ASF Remote Management and Control Protocol (ASF-RMCP) & IPMI Remote Management Protocol';
        $this->portsWellKnown[625]['tcp'] = 'Open Directory Proxy (ODProxy)';
        $this->portsWellKnown[631]['udp'] = 'IPP / CUPS Common Unix printing system (Il server di stampa sui sistemi operativi UNIX/Linux)';
        $this->portsWellKnown[636]['tcp'] = 'LDAP su SSL';
        $this->portsWellKnown[647]['tcp'] = 'DHCP Failover protocol';
        $this->portsWellKnown[648]['tcp'] = 'Registry Registrar Protocol (RRP)';
        $this->portsWellKnown[654]['tcp'] = 'Media Management System (MMS) Media Management Protocol (MMP)';
        $this->portsWellKnown[660]['tcp'] = 'Mac OS X Server administration ver 10.4 and earlier';
        $this->portsWellKnown[666]['tcp'] = 'Doom giocato in rete via TCP';
        $this->portsWellKnown[674]['tcp'] = 'Application Configuration Access Protocol (ACAP) ';
        $this->portsWellKnown[691]['tcp'] = 'MS Exchange Routing ';
        $this->portsWellKnown[695]['tcp'] = 'IEEE Media Management System over SSL (IEEE-MMS-SSL)';
        $this->portsWellKnown[698]['udp'] = 'Optimized Link State Routing (OLSR) ';
        $this->portsWellKnown[700]['tcp'] = 'Extensible Provisioning Protocol (EPP), a protocol for communication between domain name registries and registrars (RFC 5734)';
        $this->portsWellKnown[701]['tcp'] = 'Link Management Protocol (LMP), a protocol that runs between a pair of nodes and is used to manage traffic engineering (TE) links';
        $this->portsWellKnown[702]['tcp'] = 'IRIS (Internet Registry Information Service) over BEEP (Blocks Extensible Exchange Protocol) (RFC 3983)';
        $this->portsWellKnown[706]['tcp'] = 'Secure Internet Live Conferencing (SILC) ';
        $this->portsWellKnown[711]['tcp'] = 'Cisco Tag Distribution Protocol - being replaced by the MPLS Label Distribution Protocol';
        $this->portsWellKnown[712]['tcp'] = 'Topology Broadcast based on Reverse-Path Forwarding routing protocol TBRPF (RFC3684)';
        $this->portsWellKnown[783]['tcp'] = 'SpamAssassin spamd daemon ';
        $this->portsWellKnown[808]['tcp'] = 'Microsoft Net.TCP Port Sharing Service ';
        $this->portsWellKnown[829]['tcp'] = 'Certificate Management Protocol';
        $this->portsWellKnown[860]['tcp'] = 'iSCSI (RFC 3720)';
        $this->portsWellKnown[873]['tcp'] = 'rsync file synchronization protocol ';
        $this->portsWellKnown[888]['tcp'] = 'IBM Endpoint Manager Remote Control ';
        $this->portsWellKnown[903]['tcp'] = 'VMware ESXi';
        $this->portsWellKnown[953]['tcp'] = 'BIND remote name daemon control (RNDC)';
        $this->portsWellKnown[981]['tcp'] = 'Remote HTTPS management for firewall devices running embedded Check Point VPN-1 software';
        $this->portsWellKnown[987]['tcp'] = 'Microsoft Remote Web Workplace, a feature of Windows Small Business Server';
        $this->portsWellKnown[993]['tcp'] = 'IMAP4 Internet Message Access Protocol (E-mail) su SSL';
        $this->portsWellKnown[995]['tcp'] = 'POP3 Post Office Protocol (E-mail) su SSL';
        $this->portsWellKnown[1010]['tcp'] = 'ThinLinc web-based administration interface';
    }
    /**
     * setRegisteredPorts
     * Set a private class array containing Registered Ports
     *
     * @return void
     * @author n4d46t3m
     * @version 1.0.1 
     */
    private function setRegisteredPorts(){
        // Incomplete, taken from https://it.wikipedia.org/wiki/Lista_di_porte_standard and
        // https://en.wikipedia.org/wiki/List_of_TCP_and_UDP_port_numbers#Registered_ports
        $this->portsRegistered[1080]['tcp'] = 'SOCKS Proxy';
        $this->portsRegistered[1099]['tcp'] = 'rmiregistry, Java remote method invocation (RMI) registry';
        $this->portsRegistered[1113]['udp'] = 'Licklider Transmission Protocol (LTP) delay tolerant networking protocol';
        $this->portsRegistered[1194]['udp'] = 'OpenVPN';
        $this->portsRegistered[1220]['tcp'] = 'QuickTime Streaming Server administration';
        $this->portsRegistered[1433]['tcp'] = 'Microsoft-SQL-Server';
        $this->portsRegistered[1434]['tcp'] = 'Microsoft-SQL-Monitor';
        $this->portsRegistered[1434]['udp'] = 'Microsoft-SQL-Monitor';
        $this->portsRegistered[1500]['tcp'] = 'IBM Tivoli Storage Manager server';
        $this->portsRegistered[1883]['tcp'] = 'MQTT';
        $this->portsRegistered[1900]['udp'] = 'Simple Service Discovery Protocol (SSDP), discovery of UPnP devices';
        $this->portsRegistered[1984]['tcp'] = 'Big Brother';
        $this->portsRegistered[1985]['udp'] = 'Cisco Hot Standby Router Protocol (HSRP)';
        $this->portsRegistered[2095]['tcp'] = 'cPanel default web mail';
        $this->portsRegistered[2049]['udp'] = 'Network File System';
        $this->portsRegistered[2101]['tcp'] = 'rtcm-sc104 usato per le correzioni differenziali dei gps';
        $this->portsRegistered[2101]['udp'] = 'rtcm-sc104 usato per le correzioni differenziali dei gps';
        $this->portsRegistered[2761]['tcp'] = 'DICOM ISCL (Integrated Secure Communication Layer)';
        $this->portsRegistered[2761]['udp'] = 'DICOM ISCL (Integrated Secure Communication Layer)';
        $this->portsRegistered[2762]['tcp'] = 'DICOM TLS';
        $this->portsRegistered[2762]['udp'] = 'DICOM TLS';
        $this->portsRegistered[3050]['tcp'] = 'Firebird Database system';
        $this->portsRegistered[3128]['tcp'] = 'HTTP usato dalle web cache e porta di default per Squid cache';
        $this->portsRegistered[3306]['tcp'] = 'MySQL Database system';
        $this->portsRegistered[3389]['tcp'] = 'Desktop Remoto di Windows e Microsoft Terminal Server (RDP)';
        $this->portsRegistered[3541]['tcp'] = 'Voispeed';
        $this->portsRegistered[3542]['tcp'] = 'Voispeed';
        $this->portsRegistered[3690]['tcp'] = 'Subversion';
        $this->portsRegistered[3690]['udp'] = 'Subversion';
        $this->portsRegistered[4662]['tcp'] = 'eMule (versioni precedenti alla 0.47, le più recenti la generano casualmente)';
        $this->portsRegistered[4672]['udp'] = 'eMule (versioni precedenti alla 0.47, le più recenti la generano casualmente)';
        $this->portsRegistered[4711]['tcp'] = 'eMule Web Server';
        $this->portsRegistered[4899]['tcp'] = 'Radmin Connessione Remota';
        $this->portsRegistered[5000]['tcp'] = 'Sybase database server (default)';
        $this->portsRegistered[5060]['tcp'] = 'SIP';
        $this->portsRegistered[5060]['udp'] = 'SIP';
        $this->portsRegistered[5084]['tcp'] = 'EPCglobal Low-Level Reader Protocol (LLRP)';
        $this->portsRegistered[5084]['udp'] = 'EPCglobal Low-Level Reader Protocol (LLRP)';
        $this->portsRegistered[5085]['tcp'] = 'EPCglobal Low-Level Reader Protocol (LLRP) criptato';
        $this->portsRegistered[5085]['udp'] = 'EPCglobal Low-Level Reader Protocol (LLRP) criptato';
        $this->portsRegistered[5190]['tcp'] = 'AOL e AOL Instant Messenger';
        $this->portsRegistered[5222]['tcp'] = 'XMPP Client Connection';
        $this->portsRegistered[5269]['tcp'] = 'XMPP Server Connection';
        $this->portsRegistered[5432]['tcp'] = 'PostgreSQL Database system';
        $this->portsRegistered[5631]['tcp'] = 'Symantec PcAnywhere';
        $this->portsRegistered[5632]['udp'] = 'Symantec PcAnywhere';
        $this->portsRegistered[5800]['tcp'] = 'Ultra VNC (http)';
        $this->portsRegistered[5900]['tcp'] = 'Ultra VNC (main)';
        $this->portsRegistered[6000]['tcp'] = 'X11 usato per X-windows';
        $this->portsRegistered[6566]['tcp'] = 'SANE';
        $this->portsRegistered[6667]['tcp'] = 'IRC, Internet Relay Chat';
        $this->portsRegistered[8000]['tcp'] = 'iRDMI. Spesso usato per sbaglio al posto della porta 8080. Anche utilizzata per la gestione di pyLoad';
        $this->portsRegistered[8080]['tcp'] = 'HTTP Alternate (http-alt) o WWW caching service (web cache). Usato spesso quando un secondo server web opera sulla stessa macchina, come server proxy e di caching, o per far girare un server web come utente non di root. Si veda anche la porta 80. Tomcat usa di default questa porta';
        $this->portsRegistered[8118]['tcp'] = 'privoxy http filtering proxy service';
        $this->portsRegistered[8883]['tcp'] = 'MQTT con SSL';
        $this->portsRegistered[41951]['tcp'] = 'TVersity Media Server';
        $this->portsRegistered[41951]['udp'] = 'TVersity Media Server';
        $this->portsRegistered[44405]['tcp'] = 'Mu Online Connect Serve';
    }
    /**
     * setNotRegisteredPorts
     * Set a private class array containing NOT Registered Ports
     *
     * @return void
     * @author n4d46t3m
     * @version 1.0.0
     */
    private function setNotRegisteredPorts(){
        // Incomplete, taken from https://it.wikipedia.org/wiki/Lista_di_porte_standard
        $this->portsNotRegistered[80]['tcp'] = 'Skype';
        $this->portsNotRegistered[1337]['tcp'] = 'WASTE Encrypted File Sharing Program';
        $this->portsNotRegistered[1352]['tcp'] = 'IBM Lotus Lotus Domino/Notes';
        $this->portsNotRegistered[1521]['tcp'] = 'Oracle database default listener (CONFLITTO con l\'uso registrato: nCube License Manager)';
        $this->portsNotRegistered[2082]['tcp'] = 'CPanel, porta di default port (CONFLITTO con l\'uso registrato: Infowave Mobility Server)';
        $this->portsNotRegistered[2086]['tcp'] = 'Web Host Manager, porta di default (CONFLITTO con l\'uso registrato: GNUnet)';
        $this->portsNotRegistered[4662]['tcp'] = 'eMule AdunanzA, porta di default per il protocollo eDonkey usato da eMule AdunanzA';
        $this->portsNotRegistered[4672]['udp'] = 'eMule AdunanzA, porta di default per il protocollo eDonkey usato da eMule AdunanzA';
        $this->portsNotRegistered[5000]['tcp'] = 'Universal plug-and-play Windows network device interoperability (CONFLITTO con l\'uso registrato: complex-main)';
        $this->portsNotRegistered[5223]['tcp'] = 'XMPP porta di default per SSL Client Connection';
        $this->portsNotRegistered[5800]['tcp'] = 'VNC remote desktop protocol (per l\'uso via HTTP)';
        $this->portsNotRegistered[5900]['tcp'] = 'VNC remote desktop protocol (porta standard)';
        $this->portsNotRegistered[6881]['tcp'] = 'BitTorrent porta spesso usata';
        $this->portsNotRegistered[6969]['tcp'] = 'BitTorrent tracker port (CONFLITTO con l\'uso registrato: acmsoda)';
        $this->portsNotRegistered[9050]['tcp'] = 'TOR, porta di default per il socks5';
        $this->portsNotRegistered[9987]['udp'] = 'TeamSpeak, software VoIP proprietario, porta di default "virtual voice server"';
        $this->portsNotRegistered[9091]['tcp'] = 'Transmission, porta di default per la gestione da browser';
        $this->portsNotRegistered[10000]['tcp'] = 'Webmin interfaccia web di amministrazione';
        $this->portsNotRegistered[25565]['tcp'] = 'Minecraft videogioco di tipo sand-box';
        $this->portsNotRegistered[27960]['udp'] = '(fino a 27969) Quake 3 e videogiochi derivati da Quake 3';
        $this->portsNotRegistered[31337]['tcp'] = 'Back Orifice Remote administration tool (spesso usata dai Trojan)';
        $this->portsNotRegistered[10101]['tcp'] = 'Metin2 videogioco di tipo MMO';
    }

    /**
     * getServerDate function
     *
     * @param string 'RFC2822' or 'ISO8601'
     * @return string
     * @version 1.0.0
     */
    private function getServerDate($type){
        if($type=='RFC2822'){
            // RFC 2822 formatted date (http://www.faqs.org/rfcs/rfc2822) EG: Thu, 21 Dec 2000 16:01:07 +0200
            return date('r');
        } else if($type=='ISO8601'){
            // ISO 8601 date (added in PHP 5) EG: 2004-02-12T15:19:21+00:00
            return date('c');
        }
    }

    /**
     * getOsPhpBuild
     * This function use the PHP_OS constant 
     * 
     * @return string that contain the operating system PHP was built on
     * @version 1.0.0
    */
    private function getOsPhpBuild(){
        return PHP_OS;
    }

    /**
     * sysUname
     * using php_uname returns information about the operating system PHP is running on
     *
     * @return string that contain a description of the operating system PHP is running on
     * @version 1.0.0
     */
    private function sysUname(){
        return php_uname();
    }

    /**
     * isWin
     * Rudimental function that check if OS is Windows
     *
     * @return boolean true if OS is Windows, otherwise false
     * @version 1.0.0
     */
    private function isWin(){
        return (strtoupper(substr($this->getOsPhpBuild(), 0, 3)) === 'WIN') ? true : false;
    }

    /**
     * phpProcessOwner
     * to get the username of the PHP process owner
     *
     * @return string
     * @version 1.0.0
     */
    private function phpProcessOwner(){
        $processUser = posix_getpwuid(posix_geteuid());
        return $processUser['name'];
    }

    /**
     * runningFileOwner
     * Gets the name of the owner of the current PHP script
     *
     * @return string
     * @version 1.0.0
     */
    private function runningFileOwner(){
        return get_current_user();
    }

    /**
     * runningFileOwnerId
     *
     * @return return array of int containing file owner UID and GID
     * @version 1.0.0
     */
    private function runningFileOwnerId(){
        return [getmyuid(),getmygid()];
    }

    /**
     * currentPhpProcess
     * Gets the current PHP process ID (the web server process)
     *
     * @return int or FALSE on error.
     * @version 1.0.0
     */
    private function currentPhpProcess(){
        return getmypid();
    }

    /**
     * phpVer
     *
     * @return string containing current phpversion
     * @version 1.0.0
     */
    private function phpVer(){
        return phpversion();
    }

    /**
     * phpVerId
     *
     * @param string $verType
     * @return mixed
     * @version 1.0.0
     */
    private function phpVerId($verType = null){
        if(defined('PHP_VERSION_ID')){
            if($verType == 'PHP_MAJOR_VERSION'){
                return PHP_MAJOR_VERSION; // The current PHP "major" version as an integer
            }else if($verType == 'PHP_MINOR_VERSION'){
                return PHP_MINOR_VERSION; // The current PHP "minor" version as an integer
            }else if($verType == 'PHP_RELEASE_VERSION'){
                return PHP_RELEASE_VERSION; // The current PHP "release" version as an integer
            }else if($verType == 'PHP_EXTRA_VERSION'){
                return PHP_EXTRA_VERSION; // The current PHP "extra" version as a string
            }else{
                return PHP_VERSION_ID; // The current PHP version as an integer, useful for version comparisons
            }
        }else{
            return ' PHP version is lower than 5.2.7 or PHP_VERSION_ID costant isn\'t defined or usable';
        }
    }

    /**
     * phpVerLegacy
     * PHP_VERSION is available also in PHP lower than 5.2.7
     *
     * @return string The current PHP version as a string in "major.minor.release[extra]" notation. 
     * @version 1.0.0
     */
    private function phpVerLegacy(){
        return PHP_VERSION;
    }

    /**
     * getModules
     *
     * @return array with the names of all modules compiled and loaded
     * @version 1.0.0
     */
    private function getModules(){
        return get_loaded_extensions();
    }

    /**
     * getModuleVer
     *
     * @param string moduleName
     * @return string string containing the version of given extension name
     * @version 1.0.0
     */
    private function getModuleVer($module){
        return phpversion($module);
    }

    private function getModulesVer($modules){
        $moduleVer = [];
        foreach($modules as $module){
            $moduleVer[$module] = phpversion($module);
        }
        return $moduleVer;
    }

    /**
     * getModuleFuncts
     * maybe useful also to check if module if enabled
     *
     * @param string moduleName
     * @return array with the names of the functions of a module or
     * FALSE if moduleName is not a valid extension. 
     * @version 1.0.0
     */
    private function getModuleFuncts($module){
        return get_extension_funcs($module);
    }

    /**
     * getModulesFuncts
     *
     * @param array $modules names
     * @return array
     * @version 1.0.0
     */
    private function getModulesFuncts($modules){
        $moduleFuncts = [];
        foreach($modules as $module){
            $moduleFuncts[$module] = get_extension_funcs($module);
        }
        return $moduleFuncts;
    }

    /**
     * currentDomainInUrl
     *
     * @return string that is the domain present in request
     * @version 1.0.0
     */
    private function currentDomainInUrl(){
        return $_SERVER['HTTP_HOST'];
    }

    /**
     * currentServerName
     *
     * @return string that is the value of the server name as defined in host configuration
     * @version 1.0.0
     */
    private function currentServerName(){
        return $_SERVER['SERVER_NAME'];
    }

    /**
     * tinyPortScan
     * WIP
     *
     * @param array $ports int numbers
     * @param string $protocol 'tcp' 'udp' or 'both', default 'both'
     * @return array
     * @author n4d46t3m
     * @version 1.0.2
     */
    public function tinyPortScan($ports = array(7, 21, 25, 80, 81, 110, 443, 3306), $protocol = 'both'){
        if($protocol!='tcp' and $protocol!='udp' and $protocol!='both'){return [];}
        $result = [];
        $host = $this->currentServerName();
        foreach($ports as $port){
            if($protocol == 'tcp' or $protocol == 'both'){
                $portInfo = (isset($this->portsWellKnown[$port]['tcp'])) ? ' - '.$this->portsWellKnown[$port]['tcp'] : '';
                $portInfoRegistered = (isset($this->portsRegistered[$port]['tcp'])) ? ' - '.$this->portsRegistered[$port]['tcp'] : '';
                $portInfoNotRegistered = (isset($this->portsNotRegistered[$port]['tcp'])) ? ' - '.$this->portsNotRegistered[$port]['tcp'] : '';
                //echo print_r($this->portsWellKnown[$port],true);
                $connection = @fsockopen($host, $port);
                if(is_resource($connection)){
                    $result[] = ['isOpen' => true,'proto' => 'TCP','portInfo' => $portInfo.$portInfoRegistered.$portInfoNotRegistered,'target' => $host,'targetPort' => $port,'shortPortInfo' => getservbyport($port,'tcp')];
                    //echo $portInfo.' <small>' . $host . ':' . $port . ' ' . ' TCP (' . getservbyport($port, 'tcp') . ') is open.</small><br>';
                    fclose($connection);
                }else{
                    $result[] = ['isOpen' => false,'proto' => 'TCP','portInfo' => $portInfo.$portInfoRegistered.$portInfoNotRegistered,'target' => $host,'targetPort' => $port,'shortPortInfo' => getservbyport($port,'tcp')];
                    //echo $portInfo.' <small>' . $host . ':' . $port . ' TCP (' . getservbyport($port, 'tcp') . ') is not responding.</small><br>';
                }
            }
            if($protocol == 'udp' or $protocol == 'both') {
                $portInfo = (isset($this->portsWellKnown[$port]['udp'])) ? ' - '.$this->portsWellKnown[$port]['udp'] : '';
                $portInfoRegistered = (isset($this->portsRegistered[$port]['udp'])) ? ' - '.$this->portsRegistered[$port]['udp'] : '';
                $portInfoNotRegistered = (isset($this->portsNotRegistered[$port]['udp'])) ? ' - '.$this->portsNotRegistered[$port]['udp'] : '';
                $udpSock = socket_create(AF_INET, SOCK_DGRAM, 0);
                socket_set_option($udpSock,SOL_SOCKET,SO_RCVTIMEO,array('sec'=>1,'usec'=>0));//This will set a timeout for socket_recv
                if(!socket_sendto($udpSock,'A',strlen('A'),0,$host,$port)){
                    $errorCode = socket_last_error();
                    $errorMsg = socket_strerror($errorCode);
                    //error_log('PORT'.$port.' '.$errorCode.' '.$errorMsg);//DEBUG
                    //echo $errorCode.' '.$errorMsg.'<br>';
                }
                if(socket_recv($udpSock, $reply, 1024, MSG_WAITALL) === FALSE) {
                    $errorcode = socket_last_error();
                    $errormsg = socket_strerror($errorcode);
                    $result[] = ['isOpen' => false,'proto' => 'UDP','portInfo' => $portInfo.$portInfoRegistered.$portInfoNotRegistered,'target' => $host,'targetPort' => $port,'shortPortInfo' => getservbyport($port,'udp')];
                    error_log('PORT'.$port.' Could not receive reply: ['.$errorcode.'] '.$errormsg);
                    //echo $portInfo.' <small>Could not receive reply: ['.$errorcode.'] '.$errormsg.' on '.$host.':'.$port.' UDP (' . getservbyport($port, 'udp') . ')</small><br>';
                } else {
                    $result[] = ['isOpen' => true,'proto' => 'UDP','portInfo' => $portInfo.$portInfoRegistered.$portInfoNotRegistered,'target' => $host,'targetPort' => $port,'shortPortInfo' => getservbyport($port,'udp')];
                    //echo $portInfo.' <small>' . $host . ':' . $port . ' ' . ' UDP (' . getservbyport($port, 'udp') . ') is open.</small><br>';
                    //echo $reply;
                }
                socket_close($udpSock);
            }
        }
        return $result;
    }

    /**
     * getSystemInfos
     *
     * @return array containing infos related to server OS
     * @version 1.0.0
     */
    public function getSystemInfos(){
        return [
                'Build OS' => $this->getOsPhpBuild(),
                'Running OS' => $this->sysUname(),
                'Windows' => ($this->isWin()) ? 'Sadly Yes' : 'Luckily No',
                'Server Time (RFC2822)' => $this->getServerDate('RFC2822'),
                'Server Time (ISO8601)' => $this->getServerDate('ISO8601'),
            ];
    }

    /**
     * getUserInfos
     *
     * @return array containing infos related to server user
     * @version 1.0.0
     */
    public function getUserInfos(){
        return [
                'Process Owner' => $this->phpProcessOwner(),
                'Running File Owner' => $this->runningFileOwner(),
                'Running File Owner UID' => $this->runningFileOwnerId()[0],
                'Running File Owner GID' => $this->runningFileOwnerId()[1]
            ];
    }

    /**
     * getNetworkInfos
     *
     * @return array
     * @version 1.0.0
     */
    public function getNetworkInfos(){
        return [
                'Server Name' => $this->currentServerName(),
                'Server in Request' => $this->currentDomainInUrl()
            ];
    }

    /**
     * getPhpInfos
     *
     * @return array containing infos related PHP used to run this script 
     * @version 1.0.0
     */
    public function getPhpInfos(){
        return [
                'Current Process' => $this->currentPhpProcess(),
                'PHP Version' => $this->phpVer(),
                'PHP Version ID' => $this->phpVerId(),
                'PHP Major Version' => $this->phpVerId('PHP_MAJOR_VERSION'),
                'PHP Minor Version' => $this->phpVerId('PHP_MINOR_VERSION'),
                'PHP Release Version' => $this->phpVerId('PHP_RELEASE_VERSION'),
                'PHP Extra Version' => $this->phpVerId('PHP_EXTRA_VERSION'),
                'PHP Version Legacy' => $this->phpVerLegacy(),
                'PHP Loaded Modules' => $this->getModules(),
                'PHP Modules Version' => $this->getModulesVer($this->getModules()),
                'PHP Modules Functions' => $this->getModulesFuncts($this->getModules())
            ];
    }

    /**
     * getClientUserAgent
     * @return string
     */
    public function getClientUserAgent(){
        return (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '';
    }
    /**
     * getClientOS
     * @return string
     */
    public function getClientOS(){
        $clientOS = 'UNKNOWN OS';
        foreach ($this->osList as $regex => $value)
        if(preg_match($regex, $this->getClientUserAgent()))
            $clientOS = $value;
        return $clientOS;
    }
    /**
     * getClientBrowser
     * @return string
     */
    public function getClientBrowser(){
        $browser = 'UNKNOWN Browser';
        foreach ($this->browserList as $regex => $value)
        if(preg_match($regex, $this->getClientUserAgent()))
            $browser = $value;
        return $browser;
    }

    /**
     * getClientIp
     * WIP - at this time wrong
     * @return string
     */
    public function getClientIp() {
        $ipaddress = '';
        if(isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        elseif(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        elseif(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        elseif(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        elseif(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    /**
     * getSwName
     *
     * @return string the name of this script
     */
    public function getSwName(){
        return $this->swName;
    }

    /**
     * getSwVer
     *
     * @return string the version of this script
     */
    public function getSwVer(){
        return $this->swVer;
    }

}

?>

<?php
if(basename(__FILE__)==basename($_SERVER['SCRIPT_FILENAME'])):
// HERE ARE SOME TESTS DISPLAYED ONLY IF THE PAGE IS LOADED DIRECTLY
$infos = New SysXRay;
?>
<html>
<head>
<meta name="robots" content="noindex, nofollow">
<title><?php echo $infos->getSwName().' '.$infos->getSwVer(); ?></title>
<link rel="icon" href="data:image/x-icon;base64,AAABAAEAEBAAAAEAIABoBAAAFgAAACgAAAAQAAAAIAAAAAEAIAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAGIBX/D040/w9KMf8PSjH/D0ox/w9KMf8PSjH/D0ox/w9KMf8PSjH/D0ox/w9KMf8PSjH/D0ox/w9PNP8FGhHsD081/yXAgP8jt3r/JLx+/yW9fv8lvX7/JLx9/yO3ev8jt3r/Jb1+/yW9fv8lvX7/JLx+/yO3ev8lwYH/DUAr7A9LMv8jt3n/H6Vt/RVqR/0UZkT/FGVE/xVtSP4gqXD/IKlw/RNkQ/8UZUT/FGZE/xVsSP0gqHD9I7h6/ww9KewPSzL/Jb19/xJePvsBBgT+AQQD/wEDAv8AAwL8Hptn/hqLXPsAAAD/AQQD/wEEA/8CCQb+FXBK+yS8ff8MPSnsD0sy/yW+fv8QVjn8AAIB/wAAAP8AAAD/AAAA/xmFWPsTZEP6AAAA/wAAAP8AAAD/AQQD/hRqRvkkvH3/DD0p7A9LMv8lwYD/EVk7/AACAf8AAAD/AAAA/wAAAP8QVjn5Czwn+wAAAP8AAAD/AAAA/wEEA/4Uakb5JLx9/ww9KewOSzL/HZZj/AcpG/oAAAD+AAAA/wAAAP8AAAD/Bh8V+gQUDfwAAAD/AAAA/wAAAP8AAAD+FGhF+CS8ff8MPSnsCjYj/Q1CLPock2H8GH1U+w9QNfkGIRb5AAAA/QABAf8AAAD/AAAA/gAAAP4EEw33GYRY+SGocP4juHv/DD0p7A1ELf4OSjD4FW5J+h+kbf4bjl78FWtH+QgoG/cAAQH+AAIB/gkyIfURXD32DEEq9wQWD/cQVjn5Iaty/ww9KewPTDP/JLh6/gs7J/sEFQ36Bh0T9QUbEvgDEgz6BRwT+AUXEPoBCAX9AAEB/gEHBf0FGRH5DD8q8w5KMfoKMyLrD0sy/yW/f/8RVzr8AAAA/wAAAP8BBQP+BBIM+wkyIfcIKBr4BBcP+gUbEvkGGxL6BRoR9ws4JfkRXD36CjUj6w9LMv8lvn7/EFY5/AACAf8AAAD/AAAA/wAAAP8WeFD6Elk7+gAAAP8AAgH/AAIB/wEGBP4TZEP5I7Z5/ww9KOwPSzL/Jb1+/xFbPfsBAwL+AAEB/wABAP8AAAD8HZlm/hmEWPsAAAD/AAEB/wABAf8BBgT+FW5J+iS8ff8MPSnsD0sy/yO4ev8dmmb8EFQ3/g9PNP8PTjP/EFY5/iCnb/4fpm79Dksy/w9ONP8PTzT/EFU4/h6gavwjuHv/DD0p7A9PNf8lwID/I7h7/yS9fv8kvX7/JL1+/yS8fv8jt3r/I7d6/yS9fv8kvX7/JL1+/yS9fv8juHv/JcGB/w1AK+wGIBX/D040/w9KMf8PSjH/D0ox/w9KMf8PSjH/D0ox/w9KMf8PSjH/D0ox/w9KMf8PSjH/D0ox/w9PNP8FGhHsAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA==" />
<style>
    body {font-size:0.8em; font-family:sans-serif;}
    h1, h2, h3 {color:#ffffff; text-shadow:2px 2px 4px #000000;}
    a {text-decoration:none;}
    ul {border-radius:25px; border: 2px solid #73AD21; padding:20px;}
    .noborder {border:none !important; padding-top:0px !important; padding-bottom:0px !important; padding-left:20px !important;}
    footer {width:100%; text-align:center; background-color:#73AD21; border-radius:25px; padding-top:20px; padding-bottom:20px;}
</style>
</head>
<body>
<a name="top"></a>
<div> <!-- START main content -->
    <h2>SERVER System Infos</h2>
    <ul>
    <?php foreach($infos->getSystemInfos() as $infoKey => $info): ?>
        <li>
        <?php echo '<b>'.$infoKey.'</b> => '.$info; ?>
        </li>
    <?php endforeach; ?>
        <?php
            $targetPorts = [7, 23, 25, 53, 80, 110, 443, 3306, 8080, 8081, 9999]; 
            $portScanResults = $infos->tinyPortScan($targetPorts,'tcp'); 
        ?>
        <li>
        <b>Port Scan Results</b> => on ports <?php foreach($targetPorts as $port){ echo $port.' '; } ?>
        <ul class="noborder">
        <?php 
            foreach ($portScanResults as $key => $value) {
                $isOpenColor = ($value['isOpen'])?'green':'red';
                $isOpen = ($value['isOpen'])?'OPEN':'CLOSED';
                $shortInfo = ($value['shortPortInfo'])?'('.$value['shortPortInfo'].')':'';
                $info = ($value['portInfo'])?$value['portInfo']:'';
                echo '<li style="color:'.$isOpenColor.';"><b>'.$value['proto'].'</b> '.$value['target'].':'.$value['targetPort'].' '.$shortInfo.$info.' - <b>'.$isOpen.'</b></li>';
                //error_log(print_r($value,true));//DEBUG
            }
        ?>
        </ul>
        </li>
    </ul>
    <h2>SERVER User Infos</h2>
    <ul>
    <?php foreach($infos->getUserInfos() as $infoKey => $info): ?>
        <li>
        <?php echo '<b>'.$infoKey.'</b> => '.$info; ?>
        </li>
    <?php endforeach; ?>
    </ul>
    <h2>SERVER Network Infos</h2>
    <ul>
    <?php foreach($infos->getNetworkInfos() as $infoKey => $info): ?>
        <li>
        <?php echo '<b>'.$infoKey.'</b> => '.$info; ?>
        </li>
    <?php endforeach; ?>
    </ul>
    <h2>SERVER PHP Infos</h2>
    <ul>
    <?php foreach($infos->getPhpInfos() as $infoKey => $info): ?>
        <li>
        <?php
        echo '<b>'.$infoKey.'</b> => ';
        if(is_array($info)){
        ?>
        <ul class="noborder">
        <?php foreach($info as $module => $i){ ?>
            <li>
            <?php echo $module.' => '.print_r($i,true); ?>
            </li>
        <?php } ?>
        </ul>
        <?php
        } else {
            echo $info;
        }
        ?>
        </li>
    <?php endforeach; ?>
    </ul>
    <h2>CLIENT Infos</h2>
    <ul>
        <li><b>User IP</b> => <?php echo $infos->getClientIp(); ?></li>
        <li><b>User agent</b> => <?php echo $infos->getClientUserAgent(); ?></li>
        <li><b>User OS</b> => <?php echo $infos->getClientOS(); ?></li>
        <li><b>User Browser</b> => <?php echo $infos->getClientBrowser(); ?></li>
        <li id="localeProperties"><noscript><b>User Browser Locale</b> => Can't get this info. Your browser doesn't have Javascript enabled</noscript></li>
        <li id="screenProperties"><noscript><b>User Screen Properties</b> => Can't get this info. Your browser doesn't have Javascript enabled</noscript></li>
        <li id="onLineInfoProperties"><noscript><b>Client is OnLine</b> => Can't get this info. Your browser doesn't have Javascript enabled</noscript></li>
        <li id="javaProperties"><noscript><b>JAVA Enabled</b> => Can't get this info. Your browser doesn't have Javascript enabled</noscript></li>
        <li id="cookiesProperties"><noscript><b>Cookies Enabled</b> => Can't get this info. Your browser doesn't have Javascript enabled</noscript></li>
        <li id="timeProperties"><noscript><b>User Time Infos</b> => Can't get this info. Your browser doesn't have Javascript enabled</noscript></li>
        <li id="browserProperties"><noscript><b>Browser Infos</b> => Can't get this info. Your browser doesn't have Javascript enabled</noscript></li>
    </ul>
</div> <!-- END main content -->
<footer> <!-- START footer content -->
    <a href="#top" title="Back on Top">
    <?php 
        echo $infos->getSwName().' '.$infos->getSwVer();
        $time = explode(' ', microtime());
        $finish = $time = $time[1] + $time[0];
        $total_time = round(($finish - $start), 4);
        echo ' Page loaded in '.$total_time.' secs';
    ?>
    </a>
</footer> <!-- END footer content -->
<script type="text/javascript">
    function getClientScreenProperties(){
        var scrInfo = "<b>User Screen Properties</b> => ";
        scrInfo += "<ul class=\"noborder\">";
        scrInfo += "<li>Total width/height => " + screen.width + "*" + screen.height + "</li>";
        scrInfo += "<li>Available width/height => " + screen.availWidth + "*" + screen.availHeight + "</li>";
        scrInfo += "<li>Color depth => " + screen.colorDepth + "</li>";
        scrInfo += "<li>Color resolution => " + screen.pixelDepth + "</li>";
        scrInfo += "</ul>";
        document.getElementById("screenProperties").innerHTML = scrInfo;
    }
    function getClientBrowserLocales(){
        var localeInfo = "<b>User Browser Locale</b> => ";
        localeInfo += "<ul class=\"noborder\">";
        if(navigator.languages != undefined) {
            for(var i = 0; i < navigator.languages.length;i++){
                localeInfo += "<li> Locale " + i + " => " + navigator.languages[i] + "</li>"; 
            }
        } else { 
            localeInfo += "<li>" + navigator.language + "</li>"; 
        }
        localeInfo += "</ul>";
        document.getElementById("localeProperties").innerHTML = localeInfo;
    }
    function getOnLineStatus(){
        var onLineInfo = "<b>Client is OnLine</b> => " + navigator.onLine;
        document.getElementById("onLineInfoProperties").innerHTML = onLineInfo;
    }
    function getJavaStatus(){
        var javaInfo = "<b>JAVA Enabled</b> => " + navigator.javaEnabled();
        document.getElementById("javaProperties").innerHTML = javaInfo;
    }
    function getCookiesStatus(){
        var cookiesInfo = "<b>Cookies Enabled</b> => " + navigator.cookieEnabled;
        document.getElementById("cookiesProperties").innerHTML = cookiesInfo;
    }
    function getTimeStatus(){
        var timeInfo = "<b>User Time Infos</b> => ";
        timeInfo += "<ul class=\"noborder\">";
        timeInfo += "<li>Time => " + new Date() + "</li>";
        timeInfo += "<li>TimeZone => " + (new Date()).getTimezoneOffset()/60 + "</li>";
        timeInfo += "</ul>";
        document.getElementById("timeProperties").innerHTML = timeInfo;
    }
    function getbrowserInfos(){
        var browserOnfos= "<b>Browser Infos</b> => ";
        browserOnfos += "<ul class=\"noborder\">";
        browserOnfos += "<li>Browser Name => " + navigator.appName + "</li>";
        browserOnfos += "<li>Browser Engine => " + navigator.product + "</li>";
        browserOnfos += "<li>Browser Version => " + navigator.appVersion + "</li>";
        browserOnfos += "<li>Browser User agent => " + navigator.userAgent + "</li>";
        browserOnfos += "<li>Browser Platform => " + navigator.platform + "</li>";
        browserOnfos += "</ul>";
        document.getElementById("browserProperties").innerHTML = browserOnfos;
    }
    /*
    dataCookies1(){return document.cookie},
    dataCookies2(){return decodeURIComponent(document.cookie.split(";"))},
    */
    window.onload = function() {
        getClientScreenProperties();
        getClientBrowserLocales();
        getOnLineStatus();
        getJavaStatus();
        getCookiesStatus();
        getTimeStatus();
        getbrowserInfos();
    }
</script>
</body>
</html>
<?php endif; ?>
