# Changelog

All notable changes to `ossycodes/friendlycaptcha` will be documented in this file

## 2.0.0 - 2026-04.08
- Replace the friendly-challenge scripts
```html
<script type="module" src="https://cdn.jsdelivr.net/npm/friendly-challenge@0.9.12/widget.module.min.js" async defer></script>
<script nomodule src="https://cdn.jsdelivr.net/npm/friendly-challenge@0.9.12/widget.min.js" async defer></script>
```
with the new @friendlycaptcha/sdk scripts
```html
<script type="module" src="https://cdn.jsdelivr.net/npm/@friendlycaptcha/sdk@0.2.0/site.min.js"
        async defer></script>
<script nomodule src="https://cdn.jsdelivr.net/npm/@friendlycaptcha/sdk@0.2.0/site.compat.min.js"
      async defer></script>
```

For more information visit: https://developer.friendlycaptcha.com/docs/v2/guides/upgrading-from-v1/script

## 1.0.0 - 2021-10-21

- initial release
