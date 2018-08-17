
package Cpanel::API::lsmcd;

use strict;
use warnings 'all';
use utf8;
use Sys::Hostname;

# Cpanel Dependencies
use Cpanel         ();
use Cpanel::API    ();
use Cpanel::Locale ();
use Cpanel::Logger ();

use Cpanel::AdminBin::Script::Call();
use Data::Dumper;

# Globals
my $logger;
my $locale;

sub issueSaslChangePassword {

    #Prevent potential action-at-a-distance bugs.
    #(cf. documentation for CPAN's Try::Tiny module)
    local $@;

    my ( $args, $result ) = @_;
    my $password = $args->get_length_required('password');

    my %ret =
      Cpanel::AdminBin::Call::call( 'Lsmcd', 'lsmcdAdminBin', 
                                    'ISSUE_SASL_CHANGE_PASSWORD', $password );
    my $retVar = %ret{'retVar'};
    my $output = %ret{'output'};

    $result->data(
        {
            retVar => $retVar,
            output => $output,
        }
    );
}

sub doStats {

    #Prevent potential action-at-a-distance bugs.
    #(cf. documentation for CPAN's Try::Tiny module)
    local $@;

    my ( $args, $result ) = @_;
    my $server = $args->get_length_required('server');
    my $directory = $args->get_length_required('directory');

    my %ret =
      Cpanel::AdminBin::Call::call( 'Lsmcd', 'lsmcdAdminBin', 
                                    'DO_STATS', $server, $directory );
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
