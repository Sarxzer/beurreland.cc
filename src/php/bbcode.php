<?php
function bbcode_to_html($text)
{

    $bbcodes = [
        // Bold
        '/\[b\](.*?)\[\/b\]/is' => '<strong>$1</strong>',

        // Italic
        '/\[i\](.*?)\[\/i\]/is' => '<em>$1</em>',

        // Underline
        '/\[u\](.*?)\[\/u\]/is' => '<u>$1</u>',

        // Strike
        '/\[s\](.*?)\[\/s\]/is' => '<s>$1</s>',

        // URL [url]link[/url]
        '/\[url\](https?:\/\/.*?)\[\/url\]/is' => '<a href="$1" target="_blank" rel="noopener">$1</a>',

        // URL [url=link]text[/url]
        '/\[url=(https?:\/\/.*?)\](.*?)\[\/url\]/is' => '<a href="$1" target="_blank" rel="noopener">$2</a>',

        // Image
        '/\[img\](https?:\/\/.*?)\[\/img\]/is' => '<a href="$1" target="_blank" rel="noopener"><img src="$1" alt="image" /></a>',

        // Code
        '/\[code\](.*?)\[\/code\]/is' => '<pre><code>$1</code></pre>',

        // Wave
        '/\[wave\](.*?)\[\/wave\]/is' => '<span class="wave-auto">$1</span>',
    ];

    foreach ($bbcodes as $pattern => $replace) {
        $text = preg_replace($pattern, $replace, $text);
    }

    return nl2br($text);
}