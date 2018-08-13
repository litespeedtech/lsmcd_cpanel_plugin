
package Cpanel::API::lsmcd;

use strict;
use warnings 'all';
use utf8;

# Cpanel Dependencies
use Cpanel         ();
use Cpanel::API    ();
use Cpanel::Locale ();
use Cpanel::Logger ();

use Cpanel::AdminBin::Call();
use Data::Dumper;

# Globals
my $logger;
my $locale;

sub execIssueCmd {

    #Prevent potential action-at-a-distance bugs.
    #(cf. documentation for CPAN's Try::Tiny module)
    local $@;

    my ( $args, $result ) = @_;

    my $suCmd = '/bin/bash -c ';

    my $cmd = $args->get_length_required('cmd');

    my $fullCmd = $suCmd . '"' . $cmd . '"';

    my %ret =
      Cpanel::AdminBin::Call::call( 'Lsmcd', 'lsmcdAdminBin', 'EXEC_ISSUE_CMD',
        $fullCmd );

    my $retVar = %ret{'retVar'};
    my $output = %ret{'output'};

    $result->data(
        {
            retVar => $retVar,
            output => $output,
        }
    );
}

1;
