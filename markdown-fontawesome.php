<?php
namespace Grav\Plugin;

use \Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

class MarkdownFontAwesomePlugin extends Plugin
{

    const PLUGIN_CONFIG_PATH = 'plugins.markdown-fontawesome';


    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onMarkdownInitialized' => ['onMarkdownInitialized', 0]
        ];
    }

    public function onMarkdownInitialized(Event $event)
    {
        $markdown = $event['markdown'];

        // Initialize Text example
        $markdown->addInlineType(':', 'FontAwesome');

        // Font Awesome or Line Awesome
        $fala = $this->_getConfigSetting('fala');

        if ($fala === 0) {
            $class = "la";
        } else {
            $class = "fa";
        }

        // Add function to handle this
        $markdown->inlineFontAwesome = function($excerpt) use ($class) {
            // Search $excerpt['text'] for regex and store whole matching string in $matches[0], store icon name in $matches[1]
            if (preg_match('/^:'.$class.'-([a-zA-Z0-9- ]+):/', $excerpt['text'], $matches))
            {
                return array(
                    'extent' => strlen($matches[0]),
                    'element' => array(
                        'name' => 'i',
                        'text' => '',
                        'attributes' => array(
                            'class' => $class.' '.$class.'s '.$class.'-'.$matches[1],
                        ),
                    ),
                );
            }
            elseif (preg_match('/^:'.$class.'([srldb]?) '.$class.'-([a-zA-Z0-9- ]+):/', $excerpt['text'], $matches))
            {
                return array(
                    'extent' => strlen($matches[0]),
                    'element' => array(
                        'name' => 'i',
                        'text' => '',
                        'attributes' => array(
                            'class' => $class.$matches[1].' '.$class.'-'.$matches[2],
                        ),
                    ),
                );
            }
        };
    }

    /**
     * Gets values for a specific config node of the plugin
     *
     * @param string $node
     * @return mixed
     */
    protected function _getConfigSetting($node) {
        return $this->config->get(self::PLUGIN_CONFIG_PATH . '.' . $node);
    }
}
