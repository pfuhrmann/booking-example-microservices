import amqplib, { Channel, Connection } from 'amqplib'

// rabbitmq to be global variables
let channel: Channel, connection: Connection

consume()

async function consume() {
    try {
        const amqpServer = 'amqp://rabbitmq:5672'
        connection = await amqplib.connect(amqpServer)
        channel = await connection.createChannel()

        // consume all the reservation updates
        await channel.consume('reservations', (data) => {
            const parsed = JSON.parse(data!.content.toString())
            console.log(parsed);

            channel.ack(data!);
        })

        console.log('Listening...')
    } catch (error) {
        console.log(error)
    }
}
