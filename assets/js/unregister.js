wp.domReady( () => {
    wp.blocks.unregisterBlockStyle( 'core/button', 'default' );

    // Separator: core's Default and Wide Line both render the zigzag ribbon
    // here, so drop Default and rebuild the list (picker order = registration
    // order) with Wide Line leading as the default-selected style.
    wp.blocks.unregisterBlockStyle( 'core/separator', 'default' );
    wp.blocks.unregisterBlockStyle( 'core/separator', 'wide' );
    wp.blocks.unregisterBlockStyle( 'core/separator', 'dots' );
    wp.blocks.unregisterBlockStyle( 'core/separator', 'basic' );
    
    wp.blocks.registerBlockStyle( 'core/separator', {
        name: 'wide',
        label: 'Wide Line',
        isDefault: true,
    } );
    wp.blocks.registerBlockStyle( 'core/separator', { name: 'dots', label: 'Dots' } );
    wp.blocks.registerBlockStyle( 'core/separator', { name: 'basic', label: 'Basic line' } );
});
