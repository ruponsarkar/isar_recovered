import React, { act, createRef } from 'react';
import { createRoot } from 'react-dom/client';
import FormJoditEditor from './FormJoditEditor';

// Match jodit-react 1.x: forwarded refs initially receive a textarea, while
// editorRef is called after the live editor has been constructed.
jest.mock('jodit-react', () => {
    const React = require('react');
    return React.forwardRef((props, ref) => {
        React.useLayoutEffect(() => {
            if (ref) ref.current = { value: 'Stale textarea value' };
        }, [ref]);
        React.useEffect(() => {
            props.editorRef?.({ value: '<p>Latest editor content</p>' });
        }, [props.editorRef]);
        return <button onClick={() => props.onBlur('<p>Blurred content</p>')}>Blur editor</button>;
    });
});

test('exposes the live editor and synchronizes content when focus leaves it', () => {
    global.IS_REACT_ACT_ENVIRONMENT = true;
    const container = document.createElement('div');
    document.body.appendChild(container);
    const root = createRoot(container);
    const editorRef = createRef();
    const onChange = jest.fn();
    try {
        act(() => root.render(<FormJoditEditor name="content" value="" editorRef={editorRef} onChange={onChange} />));
        expect(editorRef.current.value).toBe('<p>Latest editor content</p>');
        act(() => container.querySelector('button').click());
        expect(onChange).toHaveBeenCalledWith({ target: { name: 'content', value: '<p>Blurred content</p>' } });
    } finally {
        act(() => root.unmount());
        container.remove();
    }
});
