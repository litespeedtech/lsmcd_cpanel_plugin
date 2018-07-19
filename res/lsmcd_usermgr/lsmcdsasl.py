#!/usr/bin/python
try:
    import os, sys
    import bmemcached
except ImportError:
    print 'Missing Python bmemcached module: run "pip install python-binary-memcached"'
    sys.exit(2)

if ((len(sys.argv) == 2) and (sys.argv[1] == '-')):
    print 'Python requirements satisfied'
    sys.exit(0)

db = '/etc/sasldb2'
global fileSize

def validateDb(db):
    global fileSize
    try:
        statinfo = os.stat(db)
    except OSError as e:
        print 'Error: ', e
        return False
    
    #print db + ' is ' + str(statinfo.st_size) + ' bytes.'
    if ((statinfo.st_size < 8192) or (statinfo.st_size % 4096) or (statinfo.st_size > 1048576)):
        print 'Invalid SASL database size: ' + statinfo.st_size
        return False
    else:
        fileSize = statinfo.st_size
        return True
    
def extractEntity(data, index):
    # build up the string going backwards.
    entity = ''
    while ((index) and (data[index] > ' ')):
        entity = data[index] + entity
        index = index - 1
        
    if (entity == ''):
        return '', 0
    #print 'Entity: ' + entity
    index = index - 1
    return entity, index
    
def extractUserTuple(searchUser, block, data, index):
    # A tuple is \x01password\x01user\x00domain\x00
    end = index
    foundHere = False
    finalPassword = ''
    tuple = [ 'tag', 'Domain', 'User', 'Password' ]
    for title in tuple:
        value, index = extractEntity(data, index)
        if (index):
            if (title == 'tag'):
                if (value != 'userPassword'):
                    if (end == 4095):
                        print 'At block: ' + str(block / 4096) + ' at index: ' + str(index) + ' invalid tag: ' + value
                    else:
                        pass  # Not my first rodeo
                    return 0, foundHere, finalpassword
            elif (title == 'User'):
                user = value
            elif (title == 'Password'):
                password = value
                if (user == searchUser):
                    #print password
                    foundHere = True
                    finalPassword = password
        elif (title == 'tag'):
            # Expected end point
            return 0, foundHere, finalPassword
        else:
            print 'At block: ' + str(block / 4096) + ' Missed tuple at ' + title
            return 0, foundHere, finalPassword
    return index, foundHere, finalPassword
          
          
def readDb(user,db):
    global fileSize
    #print 'File size: ' + str(fileSize)
    block = 0
    users = 0
    found = False
    password = ''
    fd = open(db, 'r')
    while (block < fileSize):
        data = fd.read(4096)
        tag = data[4084:4095]
        if (tag in 'userPassword'):
            #print 'Block #' + str(block / 4096) + ' has tag.'
            index = 4095
            while (index):
                index, foundHere, password = extractUserTuple(user, block, data, index)
                if (index):
                    users = users + 1
                if (foundHere):
                    found = True

        block += 4096
    return users, found, password

def getUser():
    if (len(sys.argv) < 2):
        print 'Must specify server'
        sys.exit(1)
    if (len(sys.argv) > 2):
        user = sys.argv[2]
    else:
        user = ''

    return sys.argv[1], user


password = ''
server, user = getUser()
if (len(user) > 0):
    if (validateDb(db)):
        users, found, password = readDb(user,db)
        if (not found):
            print 'ERROR: User Not found'
            sys.exit(1)
    else:
        sys.exit(1)

lsmcd = bmemcached.Client((server,), user, password);
stats = lsmcd.stats()
statsValue = stats.get(server)
if (len(statsValue) == 0):
    print('Stats server access error')
    sys.exit(1)

for k, v in statsValue.iteritems():
    print k + ': ' + v

sys.exit(0)
    


