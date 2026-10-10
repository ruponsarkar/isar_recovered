import React from 'react';

// Render only generated numeric superscripts; all other content remains text.
const decodeText = text => text.replace(/&(amp|lt|gt|quot|#039);/g, (_, entity) => ({ amp: '&', lt: '<', gt: '>', quot: '"', '#039': "'" }[entity]));

export default function AuthorText({ value }) {
    return <>{decodeText(String(value || '')).split(/(<sup>[0-9*\-]+<\/sup>)/g).map((part, index) =>
        /^<sup>[0-9*\-]+<\/sup>$/.test(part)
            ? <sup key={index}>{part.slice(5, -6)}</sup>
            : <React.Fragment key={index}>{part}</React.Fragment>
    )}</>;
}
