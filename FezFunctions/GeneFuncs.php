<?php

# GeneFuncs.php
# by Nicole Ward
# <http://snowwolfegames.com>
#
# Copyright (c) 2008 - 2014 - SnowWolfe Games, LLC
#
# this script contains basic gene utility functions
#
# gene_two() - 2% chance of dominant allele
# gene_five() - 5% chance of the dominant allele
# gene_quarter() - 25% chance of dominant allele
# gene_third() - 40% chance of dominant allele
# gene_half() - 50/50 chance of either allele
# gene_threequarters() - 75% chance of dominant allele
# gene_ninetyfive() - 95% chance of dominant allele
# gene_ninetyeight() - 98% chance of dominant allele
# gene_threealleles() - 1/3 chance of one of three alleles

namespace Fez;

function gene_two($GeneRand)
{
	if ($GeneRand >= '1' &&
		$GeneRand <= '2')
	{
		$gene = '1';
	} elseif ($GeneRand >= '3' &&
		$GeneRand <= '100')
	{
		$gene = '2';
	}

	return $gene;
}


function gene_five($GeneRand)
{
	if ($GeneRand >= '1' &&
		$GeneRand <= '5')
	{
		$gene = '1';
	} elseif ($GeneRand >= '6' &&
		$GeneRand <= '100')
	{
		$gene = '2';
	}

	return $gene;
}


function gene_quarter($GeneRand)
{

	if ($GeneRand >= '1' &&
		$GeneRand <= '25')
	{
		$gene = '1';
	} elseif ($GeneRand >= '26' &&
		$GeneRand <= '100')
	{
		$gene = '2';
	}

	return $gene;
}


function gene_third($GeneRand)
{
	if ($GeneRand >= '1' &&
		$GeneRand <= '40')
	{
		$gene = '1';
	} elseif ($GeneRand >= '41' &&
		$GeneRand <= '100')
	{
		$gene = '2';
	}

	return $gene;
}


function gene_half($GeneRand)
{

	if ($GeneRand >= '1' &&
		$GeneRand <= '50')
	{
		$gene = '1';
	} elseif ($GeneRand >= '51' &&
		$GeneRand <= '100')
	{
		$gene = '2';
	}

	return $gene;
}


function gene_threequarters($GeneRand)
{

	if ($GeneRand >= '1' &&
		$GeneRand <= '75')
	{
		$gene = '1';
	} elseif ($GeneRand >= '76' &&
		$GeneRand <= '100')
	{
		$gene = '2';
	}

	return $gene;
}


function gene_ninetyfive($GeneRand)
{

	if ($GeneRand >= '1' &&
		$GeneRand <= '95')
	{
		$gene = '1';
	} elseif ($GeneRand >= '96' &&
		$GeneRand <= '100')
	{
		$gene = '2';
	}

	return $gene;
}


function gene_ninetyeight($GeneRand)
{

	if ($GeneRand >= '1' &&
		$GeneRand <= '98')
	{
		$gene = '1';
	} elseif ($GeneRand == '99' ||
		$GeneRand == '100')
	{
		$gene = '2';
	}

	return $gene;
}


function gene_threeallelesA($GeneRand)
{
	if ($GeneRand >= '1' &&
		$GeneRand <= '50')
	{
		$gene = '1';
	} elseif ($GeneRand >= '51' &&
		$GeneRand <= '75')
	{
		$gene = '2';
	} elseif ($GeneRand >= '76' &&
		$GeneRand <= '100')
	{
		$gene = '3';
	}

	return $gene;
}


function gene_threeallelesB($GeneRand)
{
	if ($GeneRand >= '1' &&
		$GeneRand <= '75')
	{
		$gene = '1';
	} elseif ($GeneRand >= '76' &&
		$GeneRand <= '95')
	{
		$gene = '2';
	} elseif ($GeneRand >= '96' &&
		$GeneRand <= '100')
	{
		$gene = '3';
	}

	return $gene;
}


function ChooseChromosome()
{
	$Rand = mt_rand(0, 1);
	switch ($Rand) {
		case 0:
			$Return = [
				'ChromosomeChosen'	 => '1',
				'OppositeChromosome' => '2'
			];
			break;
		case 1:
			$Return = [
				'ChromosomeChosen'	 => '2',
				'OppositeChromosome' => '1'
			];
			break;
	}
	return $Return;
}

