<!DOCTYPE html>
<html lang="en">
<head>
    <script type="text/javascript" src="/Box2dWeb-2.1.a.3.js"></script>
</head>
<body>
    <canvas id="canvas" width="800" height="800" style="border: 1px solid grey;"></canvas>
    <script type="text/javascript">
    var canvas = document.getElementById('canvas');
    var ctx    = canvas.getContext('2d');

    var b2Vec2          = Box2D.Common.Math.b2Vec2;
    var b2World         = Box2D.Dynamics.b2World;
    var b2Body          = Box2D.Dynamics.b2Body;
    var b2BodyDef       = Box2D.Dynamics.b2BodyDef;
    var b2Fixture       = Box2D.Dynamics.b2Fixture;
    var b2FixtureDef    = Box2D.Dynamics.b2FixtureDef;
    var b2RevJointDef = Box2D.Dynamics.Joints.b2RevoluteJointDef;
    //
    var b2CircleShape = Box2D.Collision.Shapes.b2CircleShape;
    var b2PolygonShape= Box2D.Collision.Shapes.b2PolygonShape;
    //
    var b2DebugDraw = Box2D.Dynamics.b2DebugDraw;
    //
    var gScale = 30;
    //
    var gravity= new b2Vec2(0, 9.8);
    var sleepStatic = true;
    var gWorld = new b2World(gravity, sleepStatic);
    var gFloorDef = new b2BodyDef;
    var gFloorFixtureDef, gFloor, gFloorFixture;

    gFloorDef.type= b2Body.b2_staticBody;
    gFloorDef.position.x = 800 / 2 / gScale;
    gFloorDef.position.y = 800 / gScale;
    //
    gFloorFixtureDef = new b2FixtureDef;
    gFloorFixtureDef.density = 1.0;
    gFloorFixtureDef.friction= 0.1;
    gFloorFixtureDef.restitution = 0.8;
    gFloorFixtureDef.shape = new b2PolygonShape;
    gFloorFixtureDef.shape.SetAsBox(400 / gScale, 10 / gScale);
    //
    gFloor = gWorld.CreateBody(gFloorDef);
    gFloorFixture = gFloor.CreateFixture(gFloorFixtureDef);
    //
    var debugDraw = new b2DebugDraw;
    debugDraw.SetSprite(ctx);
    debugDraw.SetDrawScale(gScale);
    debugDraw.SetFillAlpha(0.3);
    debugDraw.SetLineThickness(1.0);
    debugDraw.SetFlags(b2DebugDraw.e_shapeBit | b2DebugDraw.e_jointBit);
    //debugDraw.SetFlags(b2DebugDraw.e_shapeBit);
    gWorld.SetDebugDraw(debugDraw);
    //
    var timeStep = 1 / 60;
    var velocityIterations = 8;
    var positionIterations = 3;
    function animate()
    {
        gWorld.Step(timeStep, velocityIterations, positionIterations);
        gWorld.ClearForces();
        gWorld.DrawDebugData();
    }
    setInterval(animate, timeStep);

    function generateCircleBody()
    {
        var bodyDef = new b2BodyDef;

        bodyDef.type = b2Body.b2_dynamicBody;
        bodyDef.position.x = parseInt(Math.random() * 400) / gScale;
        bodyDef.position.y = parseInt(Math.random() * 400) / gScale;

        var bodyFixtureDef = new b2FixtureDef;
        bodyFixtureDef.density = 1.0;
        bodyFixtureDef.friction= 0.1;
        bodyFixtureDef.restitution = 1;
        bodyFixtureDef.shape = new b2CircleShape(parseInt(Math.max(5, Math.random() * 20)) / gScale);

        var body = gWorld.CreateBody(bodyDef);
        var bodyFixture = body.CreateFixture(bodyFixtureDef);
    }
    function generateRectBody()
    {
        var bodyDef = new b2BodyDef;

        bodyDef.type = b2Body.b2_dynamicBody;
        bodyDef.position.x = parseInt(Math.random() * 400) / gScale;
        bodyDef.position.y = parseInt(Math.random() * 400) / gScale;

        var bodyFixtureDef = new b2FixtureDef;
        bodyFixtureDef.density = 1.0;
        bodyFixtureDef.friction= 0.1;
        bodyFixtureDef.restitution = 0.5;
        bodyFixtureDef.shape = new b2PolygonShape;
        bodyFixtureDef.shape.SetAsBox(5 / gScale, 5 / gScale);

        var body = gWorld.CreateBody(bodyDef);
        var bodyFixture = body.CreateFixture(bodyFixtureDef);
    }

    function generatePolygonBody()
    {
        var bodyDef = new b2BodyDef;

        bodyDef.type = b2Body.b2_dynamicBody;
        bodyDef.position.x = parseInt(Math.random() * 400) / gScale;
        bodyDef.position.y = parseInt(Math.random() * 400) / gScale;

        var sqrt3 = Math.sqrt(3);
        var points = [
            new b2Vec2(5 / gScale, 5 * sqrt3 / gScale),
            new b2Vec2(10 / gScale, 0),
            new b2Vec2(5 / gScale, -5 * sqrt3 / gScale),
            new b2Vec2(-5 / gScale, -5 * sqrt3 / gScale),
            new b2Vec2(-10 / gScale, 0),
            new b2Vec2(-5 / gScale, 5 * sqrt3 / gScale),
        ];
        var bodyFixtureDef = new b2FixtureDef;
        bodyFixtureDef.density = 1.0;
        bodyFixtureDef.friction= 0.1;
        bodyFixtureDef.restitution = 0.5;
        bodyFixtureDef.shape = new b2PolygonShape;
        bodyFixtureDef.shape.SetAsArray(points);

        var body = gWorld.CreateBody(bodyDef);
        var bodyFixture = body.CreateFixture(bodyFixtureDef);
    }

    function createJointBodyPair()
    {
        var bodyDefA = new b2BodyDef;
        //bodyDefA.type= b2Body.b2_dynamicBody;
        bodyDefA.type= b2Body.b2_staticBody;
        bodyDefA.position.x = 250 / gScale;
        bodyDefA.position.y = 300 / gScale;
        var bodyAFixtureDef = new b2FixtureDef;
        bodyAFixtureDef.density = 1.0;
        bodyAFixtureDef.friction= 0.1;
        bodyAFixtureDef.restitution = 0.8;
        bodyAFixtureDef.shape = new b2PolygonShape;
        bodyAFixtureDef.shape.SetAsBox(60 / gScale, 20 / gScale);
        var bodyA = gWorld.CreateBody(bodyDefA);
        var bodyAFixture = bodyA.CreateFixture(bodyAFixtureDef);

        var bodyDefB = new b2BodyDef;
        bodyDefB.type= b2Body.b2_dynamicBody;
        bodyDefB.position.x = 330 / gScale;
        bodyDefB.position.y = 300 / gScale;
        var bodyBFixtureDef = new b2FixtureDef;
        bodyBFixtureDef.density = 1.0;
        bodyBFixtureDef.friction= 0.1;
        bodyBFixtureDef.restitution = 0.8;
        bodyBFixtureDef.shape = new b2PolygonShape;
        bodyBFixtureDef.shape.SetAsBox(40 / gScale, 20 / gScale);
        var bodyB = gWorld.CreateBody(bodyDefB);
        var bodyBFixture = bodyB.CreateFixture(bodyBFixtureDef);

        var jointDef = new b2RevJointDef;
        var jointDefCenter = new b2Vec2(305 / gScale, 300 / gScale);
        jointDef.Initialize(bodyA, bodyB, jointDefCenter);
        var joint = gWorld.CreateJoint(jointDef);
    }
    createJointBodyPair();
    generateRectBody();
    generateRectBody();
    generateRectBody();
    generateRectBody();
    generateCircleBody();
    generateCircleBody();
    generateCircleBody();
    generateRectBody();
    //generatePolygonBody();
    </script>
</body>
</html>

