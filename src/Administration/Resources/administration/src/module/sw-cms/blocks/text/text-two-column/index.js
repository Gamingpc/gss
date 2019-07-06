import { Application } from 'src/core/shopware';
import './component';
import './preview';

Application.getContainer('service').cmsService.registerCmsBlock({
    name: 'text-two-column',
    label: 'Two column text',
    category: 'text',
    component: 'sw-cms-block-text-two-column',
    previewComponent: 'sw-cms-preview-text-two-column',
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '20px',
        marginLeft: '20px',
        marginRight: '20px',
        sizingMode: 'boxed'
    },
    slots: {
        left: {
            type: 'text',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                        <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                        sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                        sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. 
                        Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. 
                        Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt 
                        ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo 
                        dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor 
                        sit amet.</p>
                        `.trim()
                    }
                }
            }
        },
        right: {
            type: 'text',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                        <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                        sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                        sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. 
                        Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. 
                        Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt 
                        ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo 
                        dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor 
                        sit amet.</p>
                        `.trim()
                    }
                }
            }
        }
    }
});
