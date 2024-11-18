const { registerBlockType } = wp.blocks;
const { RichText } = wp.blockEditor;

registerBlockType('theme/footer-block', {
    title: 'Footer Block',
    icon: 'editor-insertmore',
    category: 'widgets',
    attributes: {
        content: {
            type: 'string',
            source: 'html',
            selector: '.footer-block-content',
        },
    },
    edit({ attributes, setAttributes }) {
        const { content } = attributes;
        return (
            <RichText
                tagName="div"
                className="footer-block-content"
                value={content}
                onChange={(newContent) => setAttributes({ content: newContent })}
                placeholder="Add your footer content here..."
            />
        );
    },
    save({ attributes }) {
        const { content } = attributes;
        return (
            <div className="footer-block-content">
                <RichText.Content value={content} />
            </div>
        );
    },
});
