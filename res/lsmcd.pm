
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

sub isEA4() {

    my ( $args, $result ) = @_;

    my $ret = Cpanel::AdminBin::Call::call( 'Lsmcd', 'lsmcdAdminBin', 'IS_EA4', );

    $result->data(
        {
            isEA4 => $ret
        }
    );
}

sub getDocrootData {

    #Prevent potential action-at-a-distance bugs.
    #(cf. documentation for CPAN's Try::Tiny module)
    local $@;

    my ( $args, $result ) = @_;

    my $cmd = $args->get_length_required('cmd');

    my $ret = `$cmd`;

    $result->data(
        {
            docrootData => $ret
        }
    );
}

sub getScanDirs {

    #Prevent potential action-at-a-distance bugs.
    #(cf. documentation for CPAN's Try::Tiny module)
    local $@;

    my ( $args, $result ) = @_;

    my $cmd = $args->get_length_required('cmd');

    my $ret = `$cmd`;

    $result->data(
        {
            scanData => $ret
        }
    );
}

sub getWpPhpBinary {

    #Prevent potential action-at-a-distance bugs.
    #(cf. documentation for CPAN's Try::Tiny module)
    local $@;

    my ( $args, $result ) = @_;

    my $suCmd = _getSuCmd();

    my $cmd = $args->get_length_required('cmd');

    my $fullCmd = $suCmd . '"' . $cmd . '"';

    my $ret =
      Cpanel::AdminBin::Call::call( 'Lsmcd', 'lsmcdAdminBin', 'GET_WP_PHP_BINARY',
        $fullCmd );

    $result->data(
        {
            wpPhpBin => $ret
        }
    );
}

sub getLsmcdHomeDir {

    #Prevent potential action-at-a-distance bugs.
    #(cf. documentation for CPAN's Try::Tiny module)
    local $@;

    my ( $args, $result ) = @_;

    my $confFile = $args->get_length_required('confFile');

    my $ret =
      Cpanel::AdminBin::Call::call( 'Lsmcd', 'lsmcdAdminBin', 'GET_LSMCD_HOME_DIR',
        $confFile );

    $result->data(
        {
            lsmcdHomeDir => $ret
        }
    );
}

sub execIssueCmd {

    #Prevent potential action-at-a-distance bugs.
    #(cf. documentation for CPAN's Try::Tiny module)
    local $@;

    my ( $args, $result ) = @_;

    #my $suCmd = _getSuCmd();
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

sub _getSuCmd {
    my $username = $ENV{LOGNAME} || $ENV{USER} || getpwuid($<);

    my $suCmd = 'su ' . $username . ' -s /bin/bash -c ';

    return $suCmd;
}

1;
