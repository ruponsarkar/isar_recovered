import React, { useRef, useMemo } from "react";
import JoditEditor from "jodit-react";

const FormJoditEditor = ({
    name,
    value,
    onChange,
    height = 420,
    readonly = false
}) => {
    const editor = useRef(null);

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
            ref={editor}
            value={value}
            config={config}
            onChange={handleChange}
        />
    );
};

export default FormJoditEditor;
