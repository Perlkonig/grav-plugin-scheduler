<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class SchedulerPlugin
 * @package Grav\Plugin
 */
class SchedulerPlugin extends Plugin
{
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        // Enable the main event we are interested in
        $this->enable([
            'onPagesInitialized' => ['onPagesInitialized', 0]
        ]);
    }

    /**
     * Do some work for this event, full details of events can be found
     * on the learn site: http://learn.getgrav.org/plugins/event-hooks
     *
     * @param Event $e
     */
    public function onPagesInitialized(Event $e)
    {
        // Merge header and config
        $defaults = (array) $this->config->get('plugins.scheduler');
        /** @var Page $page */
        $page = $this->grav['page'];
        if (isset($page->header()->scheduler)) {
            $this->config->set('plugins.scheduler', array_merge($defaults, $page->header()->scheduler));
        }
        
        // If active, scan the markdown for tags
        if ($this->config->get('plugins.scheduler.active')) {
            $now = time();
            $md = $page->rawMarkdown();

            preg_match_all('/\[scheduler (.*?)\].*?\[\/scheduler\]/is', $md, $matches);
            foreach ($matches[1] as $key => $value) {
                $todel = false;
                // Check notbefore first
                if (preg_match('/notbefore\=\"(.*?)\"/i', $value, $submatch) != false) {
                    $cutoff = strtotime($submatch[1]);
                    if ($cutoff !== false) {
                        if ($now < $cutoff) {
                            $todel = true;
                        }
                    }
                }

                // Now check notafter
                if (! $todel) {
                    if (preg_match('/notafter\=\"(.*?)\"/i', $value, $submatch) != false) {
                        $cutoff = strtotime($submatch[1]);
                        if ($cutoff !== false) {
                            if ($now > $cutoff) {
                                $todel = true;
                            }
                        }
                    }
                }

                // Cut content if necessary
                if ($todel) {
                    $md = str_replace($matches[0][$key], '', $md);
                }
            }

            // Clean up remaining tags
            $md = preg_replace('/\[scheduler.*?\]/i', '', $md);
            $md = str_replace('[/scheduler]', '', $md);

            // Save the edited markup
            $this->grav['page']->rawMarkdown($md);
        }
    }
}
