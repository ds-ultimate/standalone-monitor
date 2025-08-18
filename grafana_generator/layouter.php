<?php

interface Layoutable {
    public function doLayout();
}

abstract class LayoutManager implements Layoutable {
    private LayoutColumn $mainLayout;

    public function __construct() {
        $this->mainLayout = new LayoutColumn();
    }

    public function addPanel(LayoutElement $elm) {
        $this->mainLayout->addPanel($elm);
        return $this;
    }

    public function doLayout() {
        $this->mainLayout->doLayout();
        $this->mainLayout->setLocalPosition(0, 0, null);
    }

    function getAllLayoutSubelements() {
        return $this->mainLayout->getAllLayoutSubelements();
    }
}


class LayoutElement {
    private $size = ["w" => 0, "h" => 0];
    private $localpos = ["x" => 0, "y" => 0];
    private ?LayoutElement $offsetCb = null;

    function addPos($rawArray) {
        $globalPos = $this->getGlobalPosition();
        $rawArray["gridPos"] = array_merge($globalPos, $this->size);

        return $rawArray;
    }

    public function setSize($w, $h) {
        $this->size["w"] = (int) $w;
        $this->size["h"] = (int) $h;
        return $this;
    }

    function setLocalPosition($x, $y, ?LayoutElement $offsetCb) {
        $this->localpos["x"] = (int) $x;
        $this->localpos["y"] = (int) $y;
        $this->offsetCb = $offsetCb;
    }

    public function getGridSize() {
        return $this->size;
    }

    function getAllLayoutSubelements() {
        return [$this];
    }

    public function getGlobalPosition() {
        if($this->offsetCb == null) {
            return $this->localpos;
        }

        $offset = $this->offsetCb->getGlobalPosition();
        $globalPos = [
            "x" => $this->localpos["x"] + $offset["x"],
            "y" => $this->localpos["y"] + $offset["y"],
        ];

        return $globalPos;
    }
}


class LayoutRow extends LayoutElement implements Layoutable {
    private array $subElements = [];
    private static $ROW_WIDHT = 24;

    public function addPanel(LayoutElement $elm) {
        $this->subElements[] = $elm;
        return $this;
    }

    public function doLayout() {
        $curX = 0;
        $curY = 0;
        $maxX = 0;
        $maxY = 0;

        foreach($this->subElements as $sub) {
            if($sub instanceof Layoutable) {
                $sub->doLayout();
            }
        }

        foreach($this->subElements as $sub) {
            $subPos = $sub->getGridSize();
            if($curX + $subPos["w"] > static::$ROW_WIDHT && $subPos["w"] <= static::$ROW_WIDHT) {
                $curY+= $maxY;
                $maxY = 0;
                $maxX = max($maxX, $curX);
                $curX = 0;
            }

            $sub->setLocalPosition($curX, $curY, $this);
            $curX+= $subPos["w"];
            $maxY = max($maxY, $subPos["h"]);
        }

        $this->setSize(max($curX, $maxX), $curY + $maxY);
    }

    function getAllLayoutSubelements() {
        $result = [];
        foreach($this->subElements as $sub) {
            $result = array_merge($result, $sub->getAllLayoutSubelements());
        }

        return $result;
    }
}


class LayoutColumn extends LayoutElement implements Layoutable {
    private array $subElements = [];

    public function addPanel(LayoutElement $elm) {
        $this->subElements[] = $elm;
        return $this;
    }

    public function doLayout() {
        $curY = 0;
        $maxX = 0;

        foreach($this->subElements as $sub) {
            if($sub instanceof Layoutable) {
                $sub->doLayout();
            }
        }

        foreach($this->subElements as $sub) {
            $subPos = $sub->getGridSize();

            $sub->setLocalPosition(0, $curY, $this);
            $maxX = max($maxX, $subPos["w"]);
            $curY+= $subPos["h"];
        }

        $this->setSize($maxX, $curY);
    }

    function getAllLayoutSubelements() {
        $result = [];
        foreach($this->subElements as $sub) {
            $result = array_merge($result, $sub->getAllLayoutSubelements());
        }

        return $result;
    }
}