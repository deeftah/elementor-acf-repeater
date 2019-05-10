document.addEventListener('DOMContentLoaded', function () {
    elementor.channels.editor.on('elementorThemeBuilder:ApplyPreview', function () {
        elementorCommon.ajax.addRequest('update_dynamic_tag_controls', {
            data: {
                post_id: elementor.settings.page.model.attributes.preview_id,
                tags: elementor.dynamicTags.getConfig('tags')
            },
            success: function success(data) {
                if ('[object Object]' === Object.prototype.toString.call(data.tags)) {
                    let tags = Object.keys(data.tags)

                    for (let i = 0; i < tags.length; i++) {
						let tag = tags[i]
						
                        elementor.dynamicTags.getConfig('tags')[tag]['controls']['repeater_field']['groups'] = data.tags[tag]
                    }
                }
            }
        });
    })
})