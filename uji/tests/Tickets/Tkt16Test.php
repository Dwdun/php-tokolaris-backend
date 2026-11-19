<?php

it('sanitizes malicious script and HTML inputs to prevent Stored XSS', function () {
    $sanitizerClass = class_exists('App\Support\HtmlSanitizer')
        ? 'App\Support\HtmlSanitizer'
        : (class_exists('Kunci\HtmlSanitizer') ? 'Kunci\HtmlSanitizer' : null);

    expect($sanitizerClass)->not->toBeNull();

    $malicious = "<script>alert('XSS')</script>";
    $escaped = $sanitizerClass::escape($malicious);

    expect($escaped)->not->toContain('<script>')
        ->and($escaped)->toContain('&lt;script&gt;');

    $maliciousAttr = '" onmouseover="alert(1)';
    $escapedAttr = $sanitizerClass::escape($maliciousAttr);
    expect($escapedAttr)->toContain('&quot;');
});
