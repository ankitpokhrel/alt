<?php

namespace Alt;

class Alt
{
    /** @var string */
    protected $image;

    /** @var string Default alt text */
    protected $defaultText;

    /** @var string Alt prefix */
    protected $prefix;

    /** @var int Classification threshold */
    protected $threshold = 30;

    /** @var array */
    protected $countable = ['person'];

    /**
     * Alt constructor.
     *
     * @param string $image
     * @param string $defaultText
     * @param string $altPrefix
     */
    public function __construct(
        string $image,
        string $defaultText = 'No photo description available.',
        string $altPrefix = 'Image may contain: '
    ) {
        $this->image       = $image;
        $this->prefix      = $altPrefix;
        $this->defaultText = $defaultText;
    }

    /**
     * Set image path.
     *
     * @param string $imagePath
     *
     * @return Alt
     */
    public function setImage(string $imagePath) : self
    {
        $this->image = $imagePath;

        return $this;
    }

    /**
     * Get image path.
     *
     * @return string
     */
    public function getImage() : string
    {
        return $this->image;
    }

    /**
     * Set default text.
     *
     * @param string $defaultText
     *
     * @return Alt
     */
    public function setDefaultText(string $defaultText) : self
    {
        $this->defaultText = $defaultText;

        return $this;
    }

    /**
     * Get default text.
     *
     * @return string
     */
    public function getDefaultText() : string
    {
        return $this->defaultText;
    }

    /**
     * Set alt prefix.
     *
     * @param string $prefix
     *
     * @return Alt
     */
    public function setPrefix(string $prefix) : self
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Get alt prefix.
     *
     * @return string
     */
    public function getPrefix() : string
    {
        return $this->prefix;
    }

    /**
     * Set classification threshold.
     *
     * @param int $threshold
     *
     * @return Alt
     */
    public function setThreshold(int $threshold) : self
    {
        $this->threshold = $threshold;

        return $this;
    }

    /**
     * Get classification threshold.
     *
     * @return int
     */
    public function getThreshold() : int
    {
        return $this->threshold;
    }

    /**
     * Set countable objects.
     *
     * @param array $countable
     *
     * @return Alt
     */
    public function setCountable(array $countable) : self
    {
        $this->countable = $countable;

        return $this;
    }

    /**
     * Get countable objects.
     *
     * @return array
     */
    public function getCountable() : array
    {
        return $this->countable;
    }

    /**
     * Generates alt text for an image.
     *
     * @return string
     */
    public function alt() : string
    {
        $objects = $this->arrange($this->getObjects());
        $alt     = [];

        // We will display count for objects defined in countable.
        // eg: 4 person, dining.
        foreach ($objects as $object => $count) {
            if (in_array($object, $this->countable)) {
                $alt[] = "{$objects[$object]} $object";
            } else {
                $alt[] = $object;
            }
        }

        return $this->prefix . (empty($alt) ? $this->defaultText : implode(', ', $alt));
    }

    /**
     * Get unique objects with respective count.
     *
     * @param array $objects
     *
     * @return array
     */
    public function arrange(array $objects) : array
    {
        $unique = [];

        foreach ($objects as $object) {
            if ( ! isset($unique[$object])) {
                $unique[$object] = 1;
            } else {
                $unique[$object] += 1;
            }
        }

        return $unique;
    }

    /**
     * Detect and get objects in image.
     *
     * @return array
     */
    public function getObjects() : array
    {
        $net = SsdDnn::readNetFromTensorflow();

        $net->setInput(SsdDnn::blobFromImage($this->image), '');

        $detected   = [];
        $detections = $net->forward();
        $categories = SsdDnn::getCategories();

        for ($i = 0; $i < $detections->shape[2]; $i++) {
            $classId    = $detections->atIdx([0, 0, $i, 1]);
            $confidence = intval($detections->atIdx([0, 0, $i, 2]) * 100);

            if ($classId && $confidence > $this->threshold) {
                $detected[] = $categories[$classId];
            }
        }

        return $detected;
    }
}
