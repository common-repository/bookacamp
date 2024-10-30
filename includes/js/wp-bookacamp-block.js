var bookacamp_filters;
(function (blocks, blockEditor, i18n, element) {
    var el = element.createElement;
    var __ = i18n.__;
    var SelectControl = wp.components.SelectControl;
    var InspectorControls = blockEditor.InspectorControls;
    var Placeholder = wp.components.Placeholder;
    var FocusableIframe = wp.components.FocusableIframe;
    var RawHTML = element.RawHTML;

    const wpBookacampIcon = el('img',
        {
            width: 38,
            height: 42,
            alt: 'Bookacamp logo',
            class: 'wp-bookacamp preview-logo',
            src: 'data:image/png;base64,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',
        }
    );

    blocks.registerBlockType('wp-bookacamp/searchresults', {
        apiVersion: 2,
        title: __('Bookacamp', 'wp-bookacamp'),
        icon: wpBookacampIcon,
        category: 'embed',
        description: __('Ermöglicht die einfache und direkte Einbindung der Camps/Events von Bookacamp.', 'wp-bookacamp'),
        attributes: {
            campType: {type: 'string', default: 'null'},
            label1: {type: 'string', default: 'null'},
            label2: {type: 'string', default: 'null'},
            embeddedCode: {type: 'string', default: ''},
            iframeurl: {type: 'string',},
        },

        edit: function (props) {

            function updateIframeURL() {
                if (campType && campType != 'null') {
                    var tmp_url = 'https://bookacamp.de/de/booking/form/' + campType + '/' + bookacamp_skey;
                    if (label1 && label1 != 'null') {
                        tmp_url = tmp_url + '/' + label1;
                    }
                    if (label2 && label2 != 'null') {
                        tmp_url = tmp_url + '/' + label2;
                    }
                    if (iframeurl != tmp_url) {
                        props.setAttributes({iframeurl: tmp_url});
                        bookacamp_iframe = el(FocusableIframe, {src: iframeurl, height: '500px',});
                    }
                } else {
                    iframeurl = '';
                    props.setAttributes({iframeurl: ''});
                    bookacamp_iframe = el('p', {}, 'Nach Auswahl der Sortierung wird eine Vorschau der Ergebnisse angezeigt.');
                }
            }

            function getIframeCode() {
                if (campType && campType != 'null') {
                    jQuery.ajax({
                        type: 'POST',
                        url: wp_admin_ajax_url,
                        data: {
                            action: 'get_bookacamp_iframe',
                            campType: campType,
                            label1: label1,
                            label2: label2,
                            nonce: wp_admin_ajax_nonce,
                        },
                        success: function (data, textStatus, XMLHttpRequest) {
                            embeddedCode = data;
                            props.setAttributes({embeddedCode: data});
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            console.log(errorThrown);
                        }
                    });
                } else {
                    embeddedCode = '';
                    props.setAttributes({embeddedCode: ''});
                }
            }

            function getCurrentIframe() {
                if (campType && campType != 'null') {
                    return el(FocusableIframe, {src: iframeurl, height: '500px',});
                } else {
                    return el('p', {}, 'Nach Auswahl der Sortierung wird eine Vorschau der Ergebnisse angezeigt.');
                }
            }

            function getAvailableFilters() {
                if (!bookacamp_filters) {
                    jQuery.ajax({
                        type: 'POST',
                        url: wp_admin_ajax_url,
                        async: false,
                        dataType: 'json',
                        data: {
                            action: 'get_bookacamp_filters',
                            nonce: wp_admin_ajax_nonce,
                        },
                        success: function (data, textStatus, XMLHttpRequest) {
                            bookacamp_filters = data;
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            console.log(errorThrown);
                        },
                    });
                }
            }

            getAvailableFilters();

            var options_type = bookacamp_filters.type.map(type => ({value: type.id, label: type.title}));
            var options_filter_one = bookacamp_filters.label1.map(label1 => ({value: label1.id, label: label1.title}));
            var options_filter_two = bookacamp_filters.label2.map(label2 => ({value: label2.id, label: label2.title}));

            var campType = props.attributes.campType;
            var label1 = props.attributes.label1;
            var label2 = props.attributes.label2;
            var iframeurl = props.attributes.iframeurl;
            var embeddedCode = props.attributes.embeddedCode;

            var campSelect = el(SelectControl,
                {
                    label: "Layout:",
                    value: campType,
                    options: options_type,
                    onChange: function (value) {
                        if (campType != value) {
                            campType = value;
                            props.setAttributes({campType: value});
                            getIframeCode();
                            updateIframeURL();
                        }
                    }
                }
            );
            var label1Select = el(SelectControl,
                {
                    label: "Filter 1 (optional):",
                    value: label1,
                    options: options_filter_one,
                    onChange: function (value) {
                        if (label1 != value) {
                            label1 = value;
                            props.setAttributes({label1: value});
                            getIframeCode();
                            updateIframeURL();
                        }
                    }
                }
            );
            var label2Select = el(SelectControl,
                {
                    label: "Filter 2 (optional):",
                    value: label2,
                    options: options_filter_two,
                    onChange: function (value) {
                        if (label2 != value) {
                            label2 = value;
                            props.setAttributes({label2: value});
                            getIframeCode();
                            updateIframeURL();
                        }
                    }
                }
            );

            var bookacamp_iframe = getCurrentIframe();

            return el(
                'div',
                blockEditor.useBlockProps(),
                el(Placeholder,
                    {
                        label: 'Bookacamp - Welche Veranstaltungen sollen angezeigt werden?',
                        instructions: 'Wähle zuerst die Art des Layouts. Mit den optionalen Filtern 1 und 2 kannst du einschränken, welche Suchergebnisse angezeigt werden sollen. Lasse sie einfach leer, wenn du alle Veranstaltungen einbinden möchtest.',
                        icon: wpBookacampIcon,
                        isColumnLayout: true,
                        className: 'wp-block-embed',
                    },
                    campSelect,
                    label1Select,
                    label2Select,
                    bookacamp_iframe,
                ),
                el(InspectorControls,
                    {},
                    el(
                        wp.components.PanelBody,
                        {title: 'Bookacamp', initialOpen: true},
                        el(
                            wp.components.PanelRow, {},
                            campSelect
                        ),
                        el(
                            wp.components.PanelRow, {},
                            label1Select
                        ),
                        el(
                            wp.components.PanelRow, {},
                            label2Select
                        ),
                    )
                ),
            );
        },
        save: function (props, className) {
            if (props.attributes.embeddedCode === "") {
                return el('p', {}, '');
            } else {
                return el(RawHTML, {}, props.attributes.embeddedCode);
            }
        },
        deprecated: [
            {
                attributes: {
                    text: {
                        type: 'string',
                        default: 'Keine Daten vorhanden',
                    },
                },

                migrate: function (attributes) {
                    return {embeddedCode: attributes.text,};
                },

                save: function (props) {
                    return el(RawHTML, {}, props.attributes.text);
                },
            },
        ],
    });
})(window.wp.blocks, window.wp.blockEditor, window.wp.i18n, window.wp.element, window.wp.components);