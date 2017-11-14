<?php

require_once 'CloudMusicAPI.php';


class HyperSearch
{

    const DONE_CODE = 200;
    const FAIL_CODE = 400;

    protected $API;
    protected $BR_CODE = 192000;


    public function __construct()
    {
        $this->API = new CloudMusicAPI();
    }

    /**
     * @param int|string|array $song_ids
     * @return mixed
     */
    public function url_detail_limited($song_ids)
    {
        $data = [];
        $raw = json_decode($this->API->get_play_url($song_ids, $this->BR_CODE), true, 512, JSON_BIGINT_AS_STRING);
        $code = isset($raw['code']) ? $raw['code'] : self::FAIL_CODE;
        if ($code == self::DONE_CODE) {
            foreach ($raw['data'] as $song) {
                $data[] = $song;
            }
        }
        return [
            'code' => $code,
            'list' => $data,
        ];
    }

    /**
     * @param int|string|array $song_ids
     * @return mixed
     */
    public function song_detail_limited($song_ids)
    {
        $data = [];
        $raw = json_decode($this->API->get_song_detail($song_ids), true, 512, JSON_BIGINT_AS_STRING);
        $code = isset($raw['code']) ? $raw['code'] : self::FAIL_CODE;
        if ($code == self::DONE_CODE) {
            foreach ($raw['songs'] as $song) {
                $data[] = $song;
            }
        }
        return [
            'code' => $code,
            'list' => $data,
        ];
    }

    /**
     * @param int|string $song_id
     * @return mixed
     */
    public function lyric_detail_limited($song_id)
    {
        $data = [];
        $raw = json_decode($this->API->get_song_lyric($song_id), true, 512, JSON_BIGINT_AS_STRING);
        $code = isset($raw['code']) ? $raw['code'] : self::FAIL_CODE;
        if ($code == self::DONE_CODE) {
            $data[] = $raw;
        }
        return [
            'code' => $code,
            'list' => $data,
        ];
    }

    /**
     * @param int|string $artist_id
     * @return mixed
     */
    public function artist_detail_limited($artist_id)
    {
        $data = [];
        $raw = json_decode($this->API->get_artist_detail($artist_id), true, 512, JSON_BIGINT_AS_STRING);
        $code = isset($raw['code']) ? $raw['code'] : self::FAIL_CODE;
        if ($code == self::DONE_CODE) {
            foreach ($raw['hotSongs'] as $song) {
                $data[] = $song;
            }
        }
        return [
            'code' => $code,
            'list' => $data,
        ];
    }

    /**
     * @param int|string $album_id
     * @return mixed
     */
    public function album_detail_limited($album_id)
    {
        $data = [];
        $raw = json_decode($this->API->get_album_detail($album_id), true, 512, JSON_BIGINT_AS_STRING);
        $code = isset($raw['code']) ? $raw['code'] : self::FAIL_CODE;
        if ($code == self::DONE_CODE) {
            foreach ($raw['songs'] as $song) {
                $data[] = $song;
            }
        }
        return [
            'code' => $code,
            'list' => $data,
        ];
    }

    /**
     * @param int|string $playlist_id
     * @return mixed
     */
    public function playlist_detail_limited($playlist_id)
    {
        $data = [];
        $raw = json_decode($this->API->get_playlist_detail($playlist_id), true, 512, JSON_BIGINT_AS_STRING);
        $code = isset($raw['code']) ? $raw['code'] : self::FAIL_CODE;
        if ($code == self::DONE_CODE) {
            foreach ($raw['playlist']['tracks'] as $song) {
                $data[] = $song;
            }
        }
        return [
            'code' => $code,
            'list' => $data,
        ];
    }

    /**
     * @param int|string $mv_id
     * @return mixed
     */
    public function mv_detail_limited($mv_id)
    {
        $data = [];
        $raw = json_decode($this->API->get_mv_detail($mv_id), true, 512, JSON_BIGINT_AS_STRING);
        $code = isset($raw['code']) ? $raw['code'] : self::FAIL_CODE;
        if ($code == self::DONE_CODE) {
            $data[] = $raw['data'];
        }
        return [
            'code' => $code,
            'list' => $data,
        ];
    }

    /**
     * @param int|string|array $song_ids
     * @return mixed
     */
    public function song_blend_limited($song_ids)
    {
        $data = [];
        $timestamp = time();
        $urls = json_decode($this->API->get_play_url($song_ids, $this->BR_CODE), true, 512, JSON_BIGINT_AS_STRING);
        $details = json_decode($this->API->get_song_detail($song_ids), true, 512, JSON_BIGINT_AS_STRING);
        $urls_code = isset($urls['code']) ? $urls['code'] : self::FAIL_CODE;
        $details_code = isset($details['code']) ? $details['code'] : self::FAIL_CODE;
        $code = self::FAIL_CODE;

        if ($urls_code == self::DONE_CODE && $details_code == self::DONE_CODE) {
            $code = self::DONE_CODE;
            $song_map = [];
            foreach ($urls['data'] as $song_url_data) {
                $song_map[$song_url_data['id']] = $song_url_data;
            }
            foreach ($details['songs'] as $song) {
                $artists = [];
                $song_url_data = $song_map[$song['id']];
                foreach ($song['ar'] as $artist) {
                    $artists[] = $artist['name'];
                }
                if ($song_url_data) {
                    $data[] = array_merge($song, $song_url_data, [
                        'picUrl' => $this->API->get_cover_image($song['al']['pic']),
                        'artists' => implode('/', $artists),
                        'timestamp' => $timestamp,
                    ]);
                }
            }
        }
        return [
            'code' => $code,
            'list' => $data,
        ];
    }

}
