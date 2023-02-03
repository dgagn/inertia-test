import parse from 'html-react-parser';
import purify from "dompurify";

const allowedTags = [
    "span",
    "mark",
    "b",
    "i",
];

function html(html, options = {}) {
    return parse(purify.sanitize(html, {
        ALLOWED_TAGS: allowedTags,
    }), {
        ...{
            allowedTags
        },
        ...options,
    });
}

export function __(key, replacements = {}) {
    let translation = window._translations[key] ?? key;
    for (const [placeholder, value] of Object.entries(replacements)) {
        translation = translation.replace(`:${placeholder}`, value);
    }
    const isHtml = /<[^>]*>/.test(translation);
    return isHtml ? html(translation) : translation;
}
