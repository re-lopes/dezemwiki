<?php
/**
 * DokuWiki Congrid Template: Default layout
 *
 * This default layout is used if no matching layout is found or if
 * the JSON parsing of the layout failes. This should NOT be changed.
 * 
 * @author  LarsDW223
 * @license GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

global $default_layout;

$default_layout =
'{
    "layouts":
    [
        {
            "select": [ "*" ],
            "background":"cicada-stripes",
            "top":"5vh",
            "height":"90vh",
            "grid-vert-space":"20",
            "toc":"on-page",
            "grid":
            [
                [ "title",      "title",      "title",       "content", "content", "content", "content", "content", "content", "search" ],
                [ "title",      "title",      "title",       "content", "content", "content", "content", "content", "content", "space" ],
                [ "breadcrumbs","breadcrumbs","breadcrumbs", "content", "content", "content", "content", "content", "content", "scroll-up-area"      ],
                [ "breadcrumbs","breadcrumbs","breadcrumbs", "content", "content", "content", "content", "content", "content", "pagetools"      ],
                [ "cont-side",  "cont-side",  "cont-side",   "content", "content", "content", "content", "content", "content", "pagetools"      ],
                [ "cont-side",  "cont-side",  "cont-side",   "content", "content", "content", "content", "content", "content", "pagetools"      ],
                [ "cont-side",  "cont-side",  "cont-side",   "content", "content", "content", "content", "content", "content", "pagetools"      ],
                [ "cont-side",  "cont-side",  "cont-side",   "content", "content", "content", "content", "content", "content", "pagetools"      ],
                [ "cont-side",  "cont-side",  "cont-side",   "content", "content", "content", "content", "content", "content", "pagetools"      ],
                [ "cont-side",  "cont-side",  "cont-side",   "content", "content", "content", "content", "content", "content", "scroll-down-area" ],
                [ "cont-side",  "cont-side",  "cont-side",   "content", "content", "content", "content", "content", "content", "space"          ],
                [ "cont-tools", "cont-tools", "cont-tools",  "content", "content", "content", "content", "content", "content", "space"          ],
                [ "cont-tools", "cont-tools", "cont-tools",  "content", "content", "content", "content", "content", "content", "space"          ],
                [ "cont-tools", "cont-tools", "cont-tools",  "content", "content", "content", "content", "content", "content", "space"          ],
                [ "footer",     "footer",     "footer",      "footer",  "footer",  "footer",  "footer",  "footer",  "footer",  "space"         ]
            ],
            "cells":
            [
                {
                    "id":"default",
                    "border":"semi-transparent",
                    "corners":"round"
                },
                {
                    "id":"pagetools",
                    "list-type":"no-text",
                    "border":"semi-transparent",
                    "corners":"round",
                    "css":
                    {
                        "opacity":"0.5"
                    }
                },
                {
                    "id":"cont-side",
                    "flex":
                    {
                        "direction":"column"
                    },
                    "pages": ["sidebar"],
                    "border":"semi-transparent",
                    "corners":"round",
                    "css":
                    {
                        "opacity":"0.75"
                    }
                },
                {
                    "id":"cont-tools",
                    "flex":
                    {
                        "direction":"column"
                    },
                    "items": ["usertools", "sitetools"],
                    "border":"semi-transparent",
                    "corners":"round",
                    "css":
                    {
                        "opacity":"0.75"
                    }
                },
                {
                    "id":"breadcrumbs",
                    "css":
                    {
                        "padding":"0",
                        "margin":"0",
                        "opacity":"0.75"
                    },
                    "border":"semi-transparent",
                    "items": ["trace", "youarehere"]
                },
                {
                    "id":"trace"
                },
                {
                    "id":"youarehere"
                },
                {
                    "id":"scroll-up-area",
                    "css":
                    {
                        "margin":"auto auto 10px auto",
                        "opacity":"0.5",
                        "height":"48px",
                        "width":"48px"
                    }
                },
                {
                    "id":"scroll-down-area",
                    "css":
                    {
                        "margin":"10px auto auto auto",
                        "opacity":"0.5",
                        "height":"48px",
                        "width":"48px"
                    }
                },
                {
                    "id":"sitetools"
                },
                {
                    "id":"usertools"
                },
                {
                    "id":"title",
                    "border":"semi-transparent",
                    "flex":
                    {
                        "direction":"row"
                    },
                    "css":
                    {
                        "background-color":"MediumSeaGreen",
                        "color":"white",
                        "text-shadow":"-1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000",
                        "opacity":"0.85"
                    }
                },
                {
                    "id":"search",
                    "css":
                    {
                        "font-size":"1.25em",
                        "background":"transparent",
                        "margin":"auto 0",
                        "width":"125%",
                        "padding":"0 1em"
                    }
                },
                {
                    "id":"footer",
                    "flex":
                    {
                        "direction":"column"
                    },
                    "border":"semi-transparent",
                    "corners":"round",
                    "css":
                    {
                        "margin":"auto 0",
                        "font-size":"0.75em",
                        "padding":"0.5em 2em",
                        "text-align":"center",
                        "opacity":"0.75"
                    }
                }
            ]
        },
        {
            "select":["page==config"],
            "top":"5vh",
            "height":"90vh",
            "background":"cicada-stripes",
            "toc":"off-page",
            "grid-vert-space":"20",
            "grid":
            [
                [ "title",      "title",      "title",       "content", "content", "content", "content", "content", "content", "search" ],
                [ "title",      "title",      "title",       "content", "content", "content", "content", "content", "content", "space" ],
                [ "toc",        "toc",        "toc",         "content", "content", "content", "content", "content", "content", "scroll-up-area"      ],
                [ "toc",        "toc",        "toc",         "content", "content", "content", "content", "content", "content", "pagetools"      ],
                [ "toc",        "toc",        "toc",         "content", "content", "content", "content", "content", "content", "pagetools"      ],
                [ "toc",        "toc",        "toc",         "content", "content", "content", "content", "content", "content", "pagetools"      ],
                [ "toc",        "toc",        "toc",         "content", "content", "content", "content", "content", "content", "pagetools"      ],
                [ "toc",        "toc",        "toc",         "content", "content", "content", "content", "content", "content", "pagetools"      ],
                [ "toc",        "toc",        "toc",         "content", "content", "content", "content", "content", "content", "pagetools"      ],
                [ "toc",        "toc",        "toc",         "content", "content", "content", "content", "content", "content", "scroll-down-area" ],
                [ "toc",        "toc",        "toc",         "content", "content", "content", "content", "content", "content", "space"          ],
                [ "cont-tools", "cont-tools", "cont-tools",  "content", "content", "content", "content", "content", "content", "space"          ],
                [ "cont-tools", "cont-tools", "cont-tools",  "content", "content", "content", "content", "content", "content", "space"          ],
                [ "cont-tools", "cont-tools", "cont-tools",  "content", "content", "content", "content", "content", "content", "space"          ],
                [ "footer",     "footer",     "footer",      "footer",  "footer",  "footer",  "footer",  "footer",  "footer",  "space"         ]
            ],
            "cells":
            [
                {
                    "id":"default",
                    "border":"semi-transparent",
                    "corners":"round"
                },
                {
                    "id":"pagetools",
                    "list-type":"no-text",
                    "border":"semi-transparent",
                    "corners":"round",
                    "css":
                    {
                        "opacity":"0.5"
                    }
                },
                {
                    "id":"toc",
                    "border":"semi-transparent",
                    "corners":"round",
                    "css":
                    {
                        "opacity":"0.75",
                        "overflow-y":"scroll"
                    }
                },
                {
                    "id":"title",
                    "border":"semi-transparent",
                    "flex":
                    {
                        "direction":"row"
                    },
                    "css":
                    {
                        "background-color":"MediumSeaGreen",
                        "color":"white",
                        "text-shadow":"-1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000",
                        "opacity":"0.85"
                    }
                },
                {
                    "id":"cont-tools",
                    "flex":
                    {
                        "direction":"column"
                    },
                    "items": ["usertools", "sitetools"],
                    "border":"semi-transparent",
                    "corners":"round",
                    "css":
                    {
                        "opacity":"0.75"
                    }
                },
                {
                    "id":"scroll-up-area",
                    "css":
                    {
                        "margin":"auto auto 10px auto",
                        "opacity":"0.5",
                        "height":"48px",
                        "width":"48px"
                    }
                },
                {
                    "id":"scroll-down-area",
                    "css":
                    {
                        "margin":"10px auto auto auto",
                        "opacity":"0.5",
                        "height":"48px",
                        "width":"48px"
                    }
                },
                {
                    "id":"sitetools"
                },
                {
                    "id":"usertools"
                },
                {
                    "id":"search",
                    "css":
                    {
                        "font-size":"1.25em",
                        "background":"transparent",
                        "margin":"auto 0",
                        "width":"125%",
                        "padding":"0 1em"
                    }
                },
                {
                    "id":"footer",
                    "flex":
                    {
                        "direction":"column"
                    },
                    "border":"semi-transparent",
                    "corners":"round",
                    "css":
                    {
                        "margin":"auto 0",
                        "font-size":"0.75em",
                        "padding":"0.5em 2em",
                        "text-align":"center",
                        "opacity":"0.75"
                    }
                }
            ]
        }
    ]
}';
