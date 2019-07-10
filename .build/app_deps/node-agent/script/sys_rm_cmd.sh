#!/usr/bin/env bash
PROC=rm
PARAMS=$1
if [ -f "$PARAMS" ];then
  if [ `echo "$PARAMS" | grep "/tmp/mostats/" ` ] || [ `echo "$PARAMS" | grep "/opt/swoole/config" ` ]; then
      $PROC $PARAMS 2>&1
  else
      echo "invalid params"
  fi
else
    echo "not exists"
fi
