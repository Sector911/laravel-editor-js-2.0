<?php

namespace LaravelEditorJs;

class BlocksManager
{
    protected $data;

    public function __construct($data)
    {
        $this->data = json_decode($data);
    }

    public function getData()
    {
        return $this->data;
    }

    public function getBlocks()
    {
        return $this->data->blocks ?? [];
    }

    public function getTimestamp()
    {
        return $this->data->time;
    }

    public function getVersion()
    {
        return $this->data->version;
    }

    public function hasBlocks()
    {
        return (bool)count($this->getBlocks());
    }

    public function renderHtml()
    {
        $html = '';
        foreach($this->getBlocks() as $block){
            $html .= $this->renderHtmlBlock($block);
        }

        return $html;
    }

    public function renderHtmlBlock($block)
    {
        return view('laravel-editor-js::block-'.$block->type, [
            'data' => (array)$block->data,
        ])->render();
    }

    public function countChar()
    {
        $counter = 0;

        foreach($this->data->blocks as $block){
            if(in_array($block->type, ['embed', 'image']) && isset($block->data->caption)){
                $counter += strlen($block->data->caption);
            }
            elseif($block->type == 'list' && isset($block->data->items)){
                foreach($block->data->items as $item){
                    $counter += strlen($item);
                }
            }
            elseif(isset($block->data->text)){
                $counter += strlen($block->data->text);
            }
        }

        return $counter;
    }
}