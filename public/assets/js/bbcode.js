function bbwrap(tag) {
    const textarea = document.getElementById('message');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;

    const selectedText = textarea.value.substring(start, end);

    let replacement = '';

    if (tag === 'url') {
        let url = prompt('Entrer l\'URL:');
        if (!url) return;
        if (!/^https?:\/\//i.test(url)) {
            url = 'https://' + url;
        }

        replacement = `[url=${url}]${selectedText}[/url]`;
    } else if (tag === 'img') {
        const url = prompt('Entrer l\'URL de l\'image:');
        if (!url) return;
        replacement = `[img]${url}[/img]`;
    } else {
        replacement = `[${tag}]${selectedText}[/${tag}]`;
    }

    //move the cursor to the middle of the tag if no text is selected exept for url where it will be after the url part and not for img where it will be after the whole tag
    const cursorPos = selectedText ? start + replacement.length : (tag === 'url' ? start + replacement.length - 6 : start + replacement.length - tag.length - 3);

    textarea.setRangeText(replacement, start, end, 'end');
    textarea.selectionStart = textarea.selectionEnd = cursorPos;
    textarea.focus();
}

document.getElementById('bb-bold').addEventListener('click', () => bbwrap('b'));
document.getElementById('bb-italic').addEventListener('click', () => bbwrap('i'));
document.getElementById('bb-underline').addEventListener('click', () => bbwrap('u'));
document.getElementById('bb-strikethrough').addEventListener('click', () => bbwrap('s'));
document.getElementById('bb-url').addEventListener('click', () => bbwrap('url'));
document.getElementById('bb-image').addEventListener('click', () => bbwrap('img'));
document.getElementById('bb-code').addEventListener('click', () => bbwrap('code'));