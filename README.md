Packtpub Free Learning downloader
=================================

Packtpub downloader is a script written in PHP that helps you claim your every day free eBook from [packtpub.com](https://www.packtpub.com/packt/offers/free-learning).

## How it works
The script fetches the current free eBook from [packtpub.com](https://www.packtpub.com/packt/offers/free-learning), it logs you in and claims it.
You can find your new eBook in your account's [library](https://www.packtpub.com/account/my-ebooks).
> Please note, that you need to have a [packtpub account](https://www.packtpub.com/register) to claim an eBook.

## How to use it
1. Edit packtpub-downloader.php and set your credentials
2. Run packtpub-downloader.php using the following command:
```
php -f packtpub-downloader.php
```
> Tip: You can add it to your system cronjobs to automaticaly claim your free eBook every day.

The following example shows how to claim your eBook every day at 9am:

```
0 9 * * * /usr/bin/php /path/to/files/packtpub-downloader.php >/dev/null 2>&1
```
