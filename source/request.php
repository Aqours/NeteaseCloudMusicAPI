<?php

require_once 'HyperSearch.php';


function dispatch_request() {
    $BAD_REQUEST = [
        'code' => HyperSearch::FAIL_CODE,
        'list' => [],
    ];

    if (isset($_GET['s']) && isset($_GET['id'])) {
        $result = [
            'code' => HyperSearch::FAIL_CODE,
            'list' => [],
        ];
        $hyper_search = new HyperSearch();
        $id_params = explode(',', $_GET['id']);

        switch ($_GET['s']) {
            case 'url': {
                return $hyper_search->url_detail_limited($id_params);
            }
            case 'song': {
                return $hyper_search->song_detail_limited($id_params);
            }
            case 'lyric': {
                foreach ($id_params as $id) {
                    $raw = $hyper_search->lyric_detail_limited($id);
                    if ($raw['code'] == HyperSearch::DONE_CODE) {
                        $result['code'] = $raw['code'];
                        foreach ($raw['list'] as $item) {
                            $result['list'][] = $item;
                        }
                    }
                }
                return $result;
            }
            case 'artist': {
                foreach ($id_params as $id) {
                    $raw = $hyper_search->artist_detail_limited($id);
                    if ($raw['code'] == HyperSearch::DONE_CODE) {
                        $result['code'] = $raw['code'];
                        foreach ($raw['list'] as $item) {
                            $result['list'][] = $item;
                        }
                    }
                }
                return $result;
            }
            case 'album': {
                foreach ($id_params as $id) {
                    $raw = $hyper_search->album_detail_limited($id);
                    if ($raw['code'] == HyperSearch::DONE_CODE) {
                        $result['code'] = $raw['code'];
                        foreach ($raw['list'] as $item) {
                            $result['list'][] = $item;
                        }
                    }
                }
                return $result;
            }
            case 'playlist': {
                foreach ($id_params as $id) {
                    $raw = $hyper_search->playlist_detail_limited($id);
                    if ($raw['code'] == HyperSearch::DONE_CODE) {
                        $result['code'] = $raw['code'];
                        foreach ($raw['list'] as $item) {
                            $result['list'][] = $item;
                        }
                    }
                }
                return $result;
            }
            case 'mv': {
                foreach ($id_params as $id) {
                    $raw = $hyper_search->mv_detail_limited($id);
                    if ($raw['code'] == HyperSearch::DONE_CODE) {
                        $result['code'] = $raw['code'];
                        foreach ($raw['list'] as $item) {
                            $result['list'][] = $item;
                        }
                    }
                }
                return $result;
            }
            case 'blend': {
                return $hyper_search->song_blend_limited($id_params);
            }
            case 'search':
            default:
                return $BAD_REQUEST;
        }
    } else {
        return $BAD_REQUEST;
    }
}

header('Content-Type: application/json; charset=UTF-8');

echo json_encode(dispatch_request());
exit();
