<?php

// pagination include
# $TotalRows is num_rows (total results)
# $Limit is number of rows to return


function Paginate($PageURL, $TotalRows, $Limit, $CurPage)
{
	# decide which pate we are on and set the limits to search by
	if (empty($CurPage))
	{
		$CurPage = 1;
		$LimitValue = 0;
	} else
	{
		$LimitValue = ($CurPage - 1) * $Limit;
	}

	# initialize return array. It will include the pagination navigation to echo out,
	# and the LimitValue for searching.
	$ReturnObj = new stdClass;
	$ReturnObj->LimitValue = $LimitValue;
	$ReturnObj->Menu = NULL;

	# if we aren't on page 1, we need a previous link
	if ($CurPage > 1)
	{
		$FirstPageGet = http_build_query($_GET);
		$FirstPageGet = preg_replace('/page\=[0-9]{1,}/', 'page=1', $FirstPageGet);
		$PrevPageGet = http_build_query($_GET);
		$PrevPageGet = preg_replace('/page\=[0-9]{1,}/', 'page=' . ($CurPage - 1), $PrevPageGet);
		$ReturnObj->Menu .= '<a href="' . $PageURL . '?' . $FirstPageGet . '" class="paginate padRight-medium">&lt;&lt; FIRST</a><a href="' . $PageURL . '?' . $PrevPageGet . '" class="paginate padRight-medium">&lt;&lt; PREV</a>';
	}

	# calculate the total number of pages we will have
	$NumPages = ceil($TotalRows / $Limit);

	# set up the individual page links
	if ($NumPages <= 25)
	{
		# if it is less than 25 pages, show a link for each page
		for ($i = 1; $i <= $NumPages; $i++)
		{
			if ($CurPage == $i)
			{
				$ReturnObj->Menu .= '<span class="padRight-medium">[ ' . $i . ' ]</span> ';
			} else
			{
				if (isset($_GET['page']))
				{
					$PageListGet = http_build_query($_GET);
					$PageListGet = preg_replace('/page\=[0-9]{1,}/', 'page=' . $i, $PageListGet);
					$ReturnObj->Menu .= '<a href="' . $PageURL . '?' . $PageListGet . '" class="pageinate padRight-medium">' . $i . '</a> ';
				} else
				{
					$ReturnObj->Menu .= '<a href="' . $PageURL . '?page=' . $i . '&' . http_build_query($_GET) . '" class="paginate padRight-medium">' . $i . '</a> ';
				}
			}
		}
	} else
	{
		# if it is more than 25 pages, just print the last five & next five from current page, and last eight?
		# if curpage is less than six, $LastFivePage = 1
		if ($CurPage < 6)
		{
			$LastFivePage = 1;
		} else
		{
			$LastFivePage = $CurPage - 5;
		}
		# if $CurPage is within 5 of the last page, $NextFivePage = $NumPages
		if ($CurPage > ($NumPages - 5))
		{
			$NextFivePage = $NumPages;
		} else
		{
			$NextFivePage = $CurPage + 5;
		}
		for ($i = $LastFivePage; $i <= $NextFivePage; $i++)
		{
			if ($CurPage == $i)
			{
				$ReturnObj->Menu .= '<span class="padRight-medium">[ ' . $i . ' ]</span>';
			} else
			{
				if (isset($_GET['page']))
				{
					$PageListGet = http_build_query($_GET);
					$PageListGet = preg_replace('/page\=[0-9]{1,}/', 'page=' . $i, $PageListGet);
					$ReturnObj->Menu .= '<a href="' . $PageURL . '?' . $PageListGet . '" class="paginate padRight-medium">' . $i . '</a> ';
				} else
				{
					$ReturnObj->Menu .= '<a href="' . $PageURL . '?page=' . $i . '&' . http_build_query($_GET) . '" class="paginate padRight-medium">' . $i . '</a> ';
				}
			}
		}

		# if $NextFivePage doesn't take us to the end of the pages, do the next 8 links
		if ($NextFivePage != $NumPages)
		{
			$ReturnObj->Menu .= ' ... ';
			# if $NextFivePage is within 8 of the last page, only print the remainder
			if ($NextFivePage > ($NumPages - 8))
			{
				$LastEight = $NextFivePage + 1;
			} else
			{
				$LastEight = $NumPages - 8;
				for ($i = $LastEight; $i <= $NumPages; $i++)
				{
					if ($CurPage == $i)
					{
						$ReturnObj->Menu .= '<span class="padRight-medium">[ ' . $i . ' ]</span>';
					} else
					{
						if (isset($_GET['page']))
						{
							$PageListGet = http_build_query($_GET);
							$PageListGet = preg_replace('/page\=[0-9]{1,}/', 'page=' . $i, $PageListGet);
							$ReturnObj->Menu .= '<a href="' . $PageURL . '?' . $PageListGet . '" class="paginate padRight-medium">' . $i . '</a> ';
						} else
						{
							$ReturnObj->Menu .= '<a href="' . $PageURL . '?page=' . $i . '&' . http_build_query($_GET) . '" class="paginate padRight-medium">' . $i . '</a> ';
						}
					}
				}
			}
		}
	}

	# if the current page isn't the last page, we need the next link
	if ($CurPage < $NumPages)
	{
		if (isset($_GET['page']))
		{
			$NextPageGet = http_build_query($_GET);
			$NextPageGet = preg_replace('/page\=[0-9]{1,}/', 'page=' . ($CurPage + 1), $NextPageGet);
			$ReturnObj->Menu .= '<a href="' . $PageURL . '?' . $NextPageGet . '" class="paginate">NEXT &gt;&gt;</a>';
		} else
		{
			$ReturnObj->Menu .= '<a href="' . $PageURL . '?page=' . ($CurPage + 1) . '&' . http_build_query($_GET) . '" class="paginate">NEXT &gt;&gt;</a>';
		}
	}

	# get the showing x results of y so players know where they are in the results
	$a = $CurPage * $Limit;
	if ($a > $TotalRows)
	{
		$a = $TotalRows;
	}

	$b = (($CurPage - 1) * $Limit) + 1;

	$ReturnObj->Menu .= '<br /><span class="bold">Showing results ' . number_format($b) . ' to ' . number_format($a) . ' of ' . number_format($TotalRows) . '</span>';

	# return the results
	return $ReturnObj;
}


?>