<?php

namespace BitbucketWebhooks\Tests;

class HelpersGetBranchTest extends Base
{

    public function testGetBranch()
    {
        $this->loger->expects($this->once())
            ->method('error')
            ->with($this->anything(), $this->anything());
        $branch = \BitbucketWebhooks\helpers\getBranch(
            $this->badBodyDataProvider(),
            $this->loger
        );
        $this->assertEquals('', $branch);

        $this->loger->expects($this->never())
            ->method('error')
            ->with($this->anything(), $this->anything());
        $branch = \BitbucketWebhooks\helpers\getBranch(
            $this->bodyDataProvider(),
            $this->loger
        );
        $this->assertEquals('master', $branch);
    }

    public function badBodyDataProvider()
    {
        $json = <<<JSON
      {"actor": {
    "type": "user",
    "username": "emmap1",
    "display_name": "Emma",
    "uuid": "{a54f16da-24e9-4d7f-a3a7-b1ba2cd98aa3}",
    "links": {
      "self": {
        "href": "https://api.bitbucket.org/api/2.0/users/emmap1"
      },
      "html": {
        "href": "https://api.bitbucket.org/emmap1"
      },
      "avatar": {
        "href": "https://avatar.png"
      }
    }
  },
  "pullrequest": "miss",
  "repository": {
    "type": "repository",
    "links": {
      "self": {
        "href": "https://api.bitbucket.org/api/2.0/repositories/bitbucket/bitbucket"
      },
      "html": {
        "href": "https://api.bitbucket.org/bitbucket/bitbucket"
      },
      "avatar": {
        "href": "https://avatar.png"
      }
    },
    "uuid": "{673a6070-3421-46c9-9d48-90745f7bfe8e}",
    "project": "Project",
    "full_name": "team_name/repo_name",
    "name": "repo_name",
    "website": "https://mywebsite.com/",
    "owner": "Owner",
    "scm": "git",
    "is_private": true
  }
}
JSON;

        return json_decode($json);
    }

    public function bodyDataProvider()
    {
        $json = <<<JSON
{
  "actor": {
    "type": "user",
    "username": "emmap1",
    "display_name": "Emma",
    "uuid": "{a54f16da-24e9-4d7f-a3a7-b1ba2cd98aa3}",
    "links": {
      "self": {
        "href": "https://api.bitbucket.org/api/2.0/users/emmap1"
      },
      "html": {
        "href": "https://api.bitbucket.org/emmap1"
      },
      "avatar": {
        "href": "https://avatar.png"
      }
    }
  },
  "pullrequest": {
    "id" :  1 ,
    "title" :  "Title of pull request" ,
    "description" :  "Description of pull request" ,
    "state" :  "OPEN|MERGED|DECLINED" ,
    "author" : "User",
    "source" : {
      "branch" : {  "name" :  "branch2"  },
      "commit" : {  "hash" :  "d3022fc0ca3d"  },
      "repository" : "Repository"
    },
    "destination" : {
      "branch" : {  "name" :  "master"  },
      "commit" : {  "hash" :  "ce5965ddd289"  },
      "repository" : "Repository"
    },
    "merge_commit" : {  "hash" :  "764413d85e29"  },
    "participants" : ["User"],
    "reviewers" : ["User"],
    "close_source_branch" :  true ,
    "closed_by" : "User",
    "reason" :  "reason for declining the PR (if applicable)" ,
    "created_on" :  "2015-04-06T15:23:38.179678+00:00" ,
    "updated_on" :  "2015-04-06T15:23:38.205705+00:00",
    "links": {
      "self": {
        "href": "https://api.bitbucket.org/api/2.0/pullrequests/pullrequest_id"
      },
      "html": {
        "href": "https://api.bitbucket.org/pullrequest_id"
      }
    }
  },
  "repository": {
    "type": "repository",
    "links": {
      "self": {
        "href": "https://api.bitbucket.org/api/2.0/repositories/bitbucket/bitbucket"
      },
      "html": {
        "href": "https://api.bitbucket.org/bitbucket/bitbucket"
      },
      "avatar": {
        "href": "https://avatar.png"
      }
    },
    "uuid": "{673a6070-3421-46c9-9d48-90745f7bfe8e}",
    "project": "Project",
    "full_name": "team_name/repo_name",
    "name": "repo_name",
    "website": "https://mywebsite.com/",
    "owner": "Owner",
    "scm": "git",
    "is_private": true
  }
}
JSON;

        return json_decode($json);
    }
}