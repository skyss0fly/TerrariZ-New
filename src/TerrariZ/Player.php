<?php
namespace TerrariZ;

class Player {
    public int $uid;               // Player Unique ID (U8)
    public int $skinVariant;       // Skin Variant (U8)
    public int $hair;              // Hair Style (U8)
    public string $name;           // Player Name (String)
    public int $hairDye;           // Hair Dye (U8)
    public int $hideVisuals;       // Hide Visuals (U8)
    public int $hideVisuals2;      // Hide Visuals 2 (U8)
    public int $hideMisc;          // Hide Miscellaneous (U8)
    public array $hairColor;       // Hair Color (3 bytes: RGB)
    public array $skinColor;       // Skin Color (3 bytes: RGB)
    public array $eyeColor;        // Eye Color (3 bytes: RGB)
    public array $shirtColor;      // Shirt Color (3 bytes: RGB)
    public array $undershirtColor; // Undershirt Color (3 bytes: RGB)
    public array $pantsColor;      // Pants Color (3 bytes: RGB)
    public array $shoeColor;       // Shoe Color (3 bytes: RGB)
    public int $difficultyFlags;   // Difficulty Flags (U8)
    public int $flags2;            // Additional Flags (U8)
    public int $flags3;            // More Flags (U8)

    public function __construct(
        int $uid, int $skinVariant, int $hair, string $name, int $hairDye,
        int $hideVisuals, int $hideVisuals2, int $hideMisc,
        array $hairColor, array $skinColor, array $eyeColor,
        array $shirtColor, array $undershirtColor, array $pantsColor, array $shoeColor,
        int $difficultyFlags, int $flags2, int $flags3
    ) {
        $this->uid = $uid;
        $this->skinVariant = $skinVariant;
        $this->hair = $hair;
        $this->name = $name;
        $this->hairDye = $hairDye;
        $this->hideVisuals = $hideVisuals;
        $this->hideVisuals2 = $hideVisuals2;
        $this->hideMisc = $hideMisc;
        $this->hairColor = $hairColor;
        $this->skinColor = $skinColor;
        $this->eyeColor = $eyeColor;
        $this->shirtColor = $shirtColor;
        $this->undershirtColor = $undershirtColor;
        $this->pantsColor = $pantsColor;
        $this->shoeColor = $shoeColor;
        $this->difficultyFlags = $difficultyFlags;
        $this->flags2 = $flags2;
        $this->flags3 = $flags3;
    }

    public function getPlayerInfo(): array {
        return [
            'uid' => $this->uid,
            'skinVariant' => $this->skinVariant,
            'hair' => $this->hair,
            'name' => $this->name,
            'hairDye' => $this->hairDye,
            'hideVisuals' => $this->hideVisuals,
            'hideVisuals2' => $this->hideVisuals2,
            'hideMisc' => $this->hideMisc,
            'hairColor' => $this->hairColor,
            'skinColor' => $this->skinColor,
            'eyeColor' => $this->eyeColor,
            'shirtColor' => $this->shirtColor,
            'undershirtColor' => $this->undershirtColor,
            'pantsColor' => $this->pantsColor,
            'shoeColor' => $this->shoeColor,
            'difficultyFlags' => $this->difficultyFlags,
            'flags2' => $this->flags2,
            'flags3' => $this->flags3,
        ];
    }
}

?>
