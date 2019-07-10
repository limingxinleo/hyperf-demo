#!/usr/bin/env bash
params=$1
params_type=$2
if [ "$params" = "" ]; then
   echo "failed: params can not be empty";
   exit 1;
fi
if [ "$params_type" = "" ]; then
   echo "failed: params_type can not be empty";
   exit 2;
fi
if [ "$params_type" = "folder" ]; then
   if [ -d "$params" ]; then
      echo "success: folder $params exists"
      exit 0;
   else
      echo "failed: folder $params not exists"
      exit 3;
   fi
fi

if [ "$params_type" = "user" ]; then
   id $params >& /dev/null
   if [ $? -ne 0 ]; then
      echo "failed: user $params do not exists"
      exit 4;
   else
      echo "success: user $params exists";
      exit 0;
   fi
fi
echo "failed: params_type : $params_type invalid"
