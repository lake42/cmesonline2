#!/usr/local/bin/perl

&Parse_Form;

sub Parse_Form {
	if ($ENV{'REQUEST_METHOD'} eq 'GET') {
		@pairs = split(/&/, $ENV{'QUERY_STRING'});
	} elsif ($ENV{'REQUEST_METHOD'} eq 'POST') {
		read (STDIN, $buffer, $ENV{'CONTENT_LENGTH'});
		@pairs = split(/&/, $buffer);
		
		if ($ENV{'QUERY_STRING'}) {
			@getpairs =split(/&/, $ENV{'QUERY_STRING'});
			push(@pairs,@getpairs);
			}
	} else {
		print "Content-type: text/html\n\n";
		print "<P>Use Post or Get";
	}

	foreach $pair (@pairs) {
		($key, $value) = split (/=/, $pair);
		$key =~ tr/+/ /;
		$key =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/eg;
		$value =~ tr/+/ /;
		$value =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/eg;
	
		$value =~s/<!--(.|\n)*-->//g;
	
		if ($formdata{$key}) {
			$formdata{$key} .= ", $value";
		} else {
			$formdata{$key} = $value;
		}
	}
}	
1;

#######################################################################

sub ErrorMessage {
	print "Content-type: text/html\n\n";
	print "The server can't open the file. It either doesn't exist or the permissions are wrong. \n";
	exit;
}


MAIN: 
{
  print "Content-type: text/html\n\n";

# Read in all the variables set by the form


		$book = $formdata{'book'};
		$chapter = $formdata{'chapter'};
		$start_verse = $formdata{'startverse'};
		$end_verse = $formdata{'endverse'};

		open (FILE,"<$book");
		@text = <FILE>;
		close(FILE);
		
		print "<html>\n";
   		print "<head>\n";
   		print "<title>The Holy Bible</title>\n";
		print "<link rel='stylesheet' type=text/css href=bible.css>\n";
   		print "</head>\n";

		print "<body bgcolor='#ffffff'>\n";
		print "<center>\n";
		print "<table width=400 border=0>\n";
		print "<tr><td><p>$text[0]</p></td></tr>\n";
		print "<tr><td>$chapter:$start_verse-$end_verse<br><br></td></tr>\n";

		$found_start = 0;
		$finished = 0;

		$verse = $start_verse;
		foreach (@text)
		{
			if ($verse > $end_verse)
			{
				last;
			}

			if ($_ =~ /(\A$chapter:$verse | $chapter:$verse )/i)
				{
				print "<tr><td>$_<P><hr></td></tr>\n";
				
				

				while ($verse < $end_verse && ($_ =~ /$chapter:$verse /i))
				{
					$verse++;
				}
				}
		}
		print "<tr><td><a href='http://www.cmesonline.org/bible/'>back to search</a></td></tr>\n";
		print "</table>\n";
		print "</center>\n";
		print "</body>\n";
}


