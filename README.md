# TweetPoll

## Jobs

- David Adeboye : Caching system
- Harri Bell-Thomas : Getting the tweets
- Henry Mattinson : Naive Bayes
- Carlos Purves : Implementing a TweetPoll twitter page
- Ben Curnow : UX Designer

## General Details
- Hosted on 2 seperate servers
- Uses a Naive Bayes Classifier
- Add up to 5 topics to search
- Topic length between 5 and 25 characters
- Analysing the last 200 tweets of each topic
- Potentially adding ads

## TweetPoll Twitter Account
- Username and Password coming soon (Carlos)

## Main pages
/index.php : Main page of the website

/processInput.php : Where all the magic happens :)

/config.php : Database details

## Servers

### Server 1 - Codename: Jean
- Hosted on DigitalOcean
- tweetpoll.co
- Public facing server
- Basically front-end and caching is done here

- David
- Ben

#### Specs
- 512MB Memory
- 1 Core Processor
- 20GB SSD
- 1TB Transfer

### Server 2 - Codename: White
- Hosted on Azure
- api.tweetpoll.co
- Private facing server, only allows connections from jean
- Backend

- Henry
- Harri
- Carlos

### Specs
- 4GB Memory
- 1 Core Processor
- 7GB SSD
- 100GB Transfer
- Can be load balanced (obvs we'll need more than 1 server)


## Special functions

http://tweetpoll.co/topic1/topic2/ will search for topic1 and topic2, up to 5 topics, allowing for easier sharing of the url.