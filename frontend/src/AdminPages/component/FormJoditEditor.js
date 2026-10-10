import React, { useRef, useMemo, useCallback } from "react";
import JoditEditor from "jodit-react";

const FormJoditEditor = ({
    name,
    value,
    onChange,
    editorRef,
    height = 420,
    readonly = false
}) => {
    const editor = useRef(null);
    // jodit-react 1.x forwards `ref` to the textarea before creating the editor.
    // Its editorRef callback supplies the live Jodit instance instead.
    const captureEditor = useCallback(instance => {
        editor.current = instance;
        if (editorRef) editorRef.current = instance;
    }, [editorRef]);

    const config = useMemo(() => ({
        uploader: {
            insertImageAsBase64URI: true,
        },
        height,
        readonly,
        triggerChangeEvent: true,
    }), [height, readonly]);

    const handleChange = (content) => {
        // mimic normal input event
        onChange({
            target: {
                name,
                value: content,
            },
        });
    };

    return (
        <JoditEditor
            editorRef={captureEditor}
            value={value}
            config={config}
            onChange={handleChange}
            onBlur={handleChange}
        />
    );
};

export default FormJoditEditor;
