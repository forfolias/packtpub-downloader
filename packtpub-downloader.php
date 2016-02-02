<?php

/**
 * Website: https://github.com/forfolias/packtpub-downloader/
 *
 * Licensed under The MIT License
 *
 * @author George Vasilakos <giorg.vasilakos@gmail.com>
 * @version 0.1
 */

/**
 * Fetches the current free e-book from packtpub.com and downloads it
 */


/**
 * Set your credentials
 */
define("ACCOUNT_EMAIL", "");
define("ACCOUNT_PASSWORD", "");


require_once "simple_html_dom.php";


downloadBook(getCurrentBook());


/**
 * Uses the simple_html_dom to find book details from the html source
 *
 * @return array
 */
function getCurrentBook() {
    $html = file_get_html("https://www.packtpub.com/packt/offers/free-learning");

    $image = $html->find('img[class=bookimage]', 0);
    $title = $html->find('div[class=dotd-title] h2', 0);
    $form_build_id = $html->find('input[name=form_build_id]', 0);
    $book_claim_url = $html->find('div[class=free-ebook] a', 0);

    return array(
        "img" => trim($image->getAttribute("src")),
        "title" => trim($title->text()),
        "form_build_id" => trim($form_build_id->getAttribute("id")),
        "book_claim_url" => trim($book_claim_url->getAttribute("href")),
    );
}


/**
 * Checks the html source to determine if you have been logged in or not
 *
 * @param $htmlSource
 * @return bool
 */
function isLoggedIn($htmlSource){
    $htmlDom = str_get_html($htmlSource);

    $loggedIn = $htmlDom->find('div[id=main-container]', 0);
    $classes = $loggedIn->getAttribute("class");

    if(strpos($classes, "not-logged-in") !== false) {
        return false;
    }
    return true;
}


/**
 * Logs in and downloads the book
 *
 * @param $book
 */
function downloadBook($book) {
    $params = array(
        "email" => ACCOUNT_EMAIL,
        "password" => ACCOUNT_PASSWORD,
        "op" => "Login",
        "form_build_id" => $book['form_build_id'],
        "form_id" => "packt_user_login_form"
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.packtpub.com/packt/offers/free-learning');
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie-name');  //could be empty, but cause problems on some hosts
    curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp');  //could be empty, but cause problems on some hosts
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $response = curl_exec($ch);
    if (curl_error($ch)) {
        echo curl_error($ch);
    }

    $loggedIn = isLoggedIn($response);

    $response = false;

    if($loggedIn) {
        curl_setopt($ch, CURLOPT_URL, 'https://www.packtpub.com'.$book['book_claim_url']);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "");
        $response = curl_exec($ch);
    }

    curl_close($ch);
    if($response === false) {
        die(1);
    }
}