/**
 * Adobe Edge: symbol definitions
 */
(function($, Edge, compId){
//images folder
var im='assets/images';

var fonts = {};    fonts['lato, sans-serif']='<script src=\"http://use.edgefonts.net/lato:n9,i4,n1,i7,i9,n7,i1,i3,n4,n3:all.js\"></script>';

var opts = {
    'gAudioPreloadPreference': 'auto',

    'gVideoPreloadPreference': 'auto'
};
var resources = [
];
var symbols = {
"stage": {
    version: "4.0.0",
    minimumCompatibleVersion: "4.0.0",
    build: "4.0.0.359",
    baseState: "Base State",
    scaleToFit: "both",
    centerStage: "both",
    initialState: "Base State",
    gpuAccelerate: false,
    resizeInstances: false,
    content: {
            dom: [
            {
                id: 'POST_FINAL_SOAT_4',
                type: 'video',
                tag: 'video',
                rect: ['0', '0','1920','1080','auto', 'auto'],
                source: ['media/video.WebM'],
                preload: 'auto'
            },
            {
                id: 'Hola',
                type: 'audio',
                tag: 'audio',
                rect: ['0', '0','320px','45px','auto', 'auto'],
                source: [loadAudio],
                preload: 'auto'
            },
            {
                id: 'Juan_Guillermo',
                type: 'text',
                rect: ['622px', '95px','auto','auto','auto', 'auto'],
                text: loadName,
                align: "center",
                font: ['lato, sans-serif', 100, "rgba(255,255,255,1.00)", "900", "none", "italic"]
            },
            {
                id: 'El_1_de_enero',
                type: 'text',
                rect: ['721px', '237px','auto','auto','auto', 'auto'],
                text: loadDate,
                align: "center",
                font: ['lato, sans-serif', 100, "rgba(255,255,255,1)", "900", "none", "italic"]
            },
            {
                id: 'Vence_el_seg',
                type: 'text',
                rect: ['605px', '344px','auto','auto','auto', 'auto'],
                text: "vence el seguro obligatorio",
                align: "center",
                font: ['lato, sans-serif', 60, "rgba(255,255,255,1)", "400", "none", "normal"]
            },
            {
                id: 'KBL_369',
                type: 'text',
                rect: ['776px', '166px','auto','auto','auto', 'auto'],
                text: loadLicense,
                align: "center",
                font: ['lato, sans-serif', 100, "rgba(255,255,255,1)", "900", "none", "italic"]
            }],
            symbolInstances: [

            ]
        },
    states: {
        "Base State": {
            "${_Juan_Guillermo}": [
                ["color", "color", 'rgba(255,255,255,1.00)'],
                ["style", "font-weight", '900'],
                ["style", "left", '638px'],
                ["style", "font-size", '100px'],
                ["style", "top", '95px'],
                ["transform", "scaleY", '0'],
                ["style", "font-style", 'italic'],
                ["transform", "scaleX", '0'],
                ["style", "text-align", 'center']
            ],
            "${_Vence_el_seg}": [
                ["style", "top", '304px'],
                ["transform", "scaleY", '0'],
                ["style", "text-align", 'center'],
                ["style", "font-style", 'normal'],
                ["transform", "scaleX", '0'],
                ["style", "font-weight", '400'],
                ["style", "left", '605px'],
                ["style", "font-size", '60px']
            ],
            "${_Stage}": [
                ["color", "background-color", 'rgba(255,255,255,1)'],
                ["style", "overflow", 'hidden'],
                ["style", "height", '1080px'],
                ["style", "width", '1920px']
            ],
            "${_KBL_369}": [
                ["style", "top", '166px'],
                ["transform", "scaleX", '0'],
                ["transform", "scaleY", '0'],
                ["style", "left", '776px']
            ],
            "${_El_1_de_enero}": [
                ["style", "top", '187px'],
                ["transform", "scaleY", '0'],
                ["transform", "scaleX", '0'],
                ["style", "left", '689px'],
                ["style", "text-align", 'center']
            ]
        }
    },
    timelines: {
        "Default Timeline": {
            fromState: "Base State",
            toState: "",
            duration: 50828,
            autoPlay: false,
            timeline: [
                { id: "eid19", tween: [ "transform", "${_Vence_el_seg}", "scaleY", '1', { fromValue: '0'}], position: 5750, duration: 1111, easing: "easeInOutElastic" },
                { id: "eid8", tween: [ "transform", "${_Juan_Guillermo}", "scaleY", '1', { fromValue: '0'}], position: 0, duration: 750, easing: "easeInOutElastic" },
                { id: "eid29", tween: [ "transform", "${_KBL_369}", "scaleX", '1', { fromValue: '0'}], position: 8000, duration: 1111, easing: "easeInOutElastic" },
                { id: "eid13", tween: [ "transform", "${_El_1_de_enero}", "scaleX", '1', { fromValue: '0'}], position: 3614, duration: 1111, easing: "easeInOutElastic" },
                { id: "eid28", tween: [ "style", "${_Juan_Guillermo}", "left", '1974px', { fromValue: '638px'}], position: 8000, duration: 1000, easing: "easeInOutExpo" },
                { id: "eid27", tween: [ "style", "${_Vence_el_seg}", "left", '1941px', { fromValue: '605px'}], position: 8000, duration: 1000, easing: "easeInOutExpo" },
                { id: "eid12", tween: [ "transform", "${_El_1_de_enero}", "scaleY", '1', { fromValue: '0'}], position: 3614, duration: 1111, easing: "easeInOutElastic" },
                { id: "eid26", tween: [ "style", "${_El_1_de_enero}", "left", '2025px', { fromValue: '689px'}], position: 8000, duration: 1000, easing: "easeInOutExpo" },
                { id: "eid34", tween: [ "style", "${_KBL_369}", "left", '-494px', { fromValue: '776px'}], position: 12000, duration: 1000, easing: "easeInOutElastic" },
                { id: "eid7", tween: [ "transform", "${_Juan_Guillermo}", "scaleX", '1', { fromValue: '0'}], position: 0, duration: 750, easing: "easeInOutElastic" },
                { id: "eid18", tween: [ "transform", "${_Vence_el_seg}", "scaleX", '1', { fromValue: '0'}], position: 5750, duration: 1111, easing: "easeInOutElastic" },
                { id: "eid30", tween: [ "transform", "${_KBL_369}", "scaleY", '1', { fromValue: '0'}], position: 8000, duration: 1111, easing: "easeInOutElastic" },
                { id: "eid1", trigger: [ function executeMediaFunction(e, data) { this._executeMediaAction(e, data); }, ['play', '${_Hola}', [] ], ""], position: 0 },
                { id: "eid38", trigger: [ function executeMediaFunction(e, data) { this._executeMediaAction(e, data); }, ['play', '${_POST_FINAL_SOAT_4}', [] ], ""], position: 0 }
           ]
        }
    }
}
};


Edge.registerCompositionDefn(compId, symbols, fonts, resources, opts);

/**
 * Adobe Edge DOM Ready Event Handler
 */
$(window).ready(function() {
     Edge.launchComposition(compId);
});
})(jQuery, AdobeEdge, "EDGE-263775848");
