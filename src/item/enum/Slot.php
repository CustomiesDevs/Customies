<?php

namespace customiesdevs\customies\item\enum;

enum Slot: string {
	case NONE = "none";
	case ARMOR = "slot.armor";
	case ARMOR_CHEST = "slot.armor.chest";
	case ARMOR_FEET = "slot.armor.feet";
	case ARMOR_HEAD = "slot.armor.head";
	case ARMOR_LEGS = "slot.armor.legs";
	case CHEST = "slot.chest";
	case ENDERCHEST = "slot.enderchest";
	case EQUIPPABLE = "slot.equippable";
	case HOTBAR = "slot.hotbar";
	case INVENTORY = "slot.inventory";
	case SADDLE = "slot.saddle";
	case WEAPON_MAIN_HAND = "slot.weapon.mainhand";
	case WEAPON_OFF_HAND = "slot.weapon.offhand";
}
